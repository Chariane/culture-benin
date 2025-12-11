<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contenu extends Model
{
    protected $primaryKey = 'id_contenu';
    protected $fillable = [
        'titre','texte','date_creation','statut','parent_id','date_validation','premium', 'prix',
        'id_region','id_langue','id_moderateur','id_type_contenu','id_auteur',
    ];

    // Casts
    protected $casts = [
        'date_creation' => 'datetime',
        'date_validation' => 'datetime',
        'premium' => 'boolean',
        'prix' => 'decimal:2',
        'statut' => 'integer',
    ];

    // Relations existantes
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue');
    }

    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_auteur');
    }

    public function moderateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_moderateur');
    }

    public function type()
    {
        return $this->belongsTo(TypeContenu::class, 'id_type_contenu');
    }

    public function medias()
    {
        return $this->hasMany(Media::class, 'id_contenu');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_contenu');
    }

    public function parent()
    {
        return $this->belongsTo(Contenu::class, 'parent_id');
    }

    public function enfants()
    {
        return $this->hasMany(Contenu::class, 'parent_id');
    }

    // NOUVELLES RELATIONS pour les fonctionnalités lecteur

    // Vues/lectures
    public function views()
    {
        return $this->hasMany(View::class, 'id_contenu');
    }

    // Likes
    public function likes()
    {
        return $this->hasMany(Like::class, 'id_contenu');
    }

    // Favoris
    public function favorisPar()
    {
        return $this->belongsToMany(Utilisateur::class, 'favoris', 'id_contenu', 'id_utilisateur')
            ->withTimestamps()
            ->withPivot('note', 'date_ajout');
    }

    // Collections
    public function dansCollections()
    {
        return $this->belongsToMany(Collection::class, 'collection_contenu', 'id_contenu', 'id_collection')
            ->withTimestamps()
            ->withPivot('ordre', 'note', 'date_ajout');
    }

    // Paiements
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_contenu');
    }

    // Lecteurs qui ont acheté ce contenu
    public function lecteursAcheteurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'paiements', 'id_contenu', 'id_lecteur')
            ->withTimestamps()
            ->withPivot(['id_transaction', 'date_paiement', 'montant', 'methode_paiement']);
    }

    // Accesseurs et méthodes utiles
    public function getRouteKeyName()
    {
        return 'id_contenu';
    }

    // Accesseur pour le nombre de vues
    public function getNombreVuesAttribute()
    {
        return $this->views()->count();
    }

    // Accesseur pour le nombre de likes
    public function getNombreLikesAttribute()
    {
        return $this->likes()->count();
    }

    // Accesseur pour le nombre de commentaires
    public function getNombreCommentairesAttribute()
    {
        return $this->commentaires()->count();
    }

    // Accesseur pour le nombre de favoris
    public function getNombreFavorisAttribute()
    {
        return $this->favorisPar()->count();
    }

    // Méthode pour vérifier si un utilisateur a aimé ce contenu
    public function estAimePar($utilisateurId)
    {
        return $this->likes()->where('id_utilisateur', $utilisateurId)->exists();
    }

    // Méthode pour vérifier si un utilisateur a ce contenu en favoris
    public function estFavoriDe($utilisateurId)
    {
        return $this->favorisPar()->where('id_utilisateur', $utilisateurId)->exists();
    }

    // Méthode pour vérifier si un utilisateur a acheté ce contenu
    public function estAchetePar($utilisateurId)
    {
        return $this->paiements()->where('id_lecteur', $utilisateurId)->exists();
    }

    // Scope pour les contenus publics (gratuits)
    public function scopePublics($query)
    {
        return $query->where('premium', false);
    }

    // Scope pour les contenus premium
    public function scopePremium($query)
    {
        return $query->where('premium', true);
    }

    // Scope pour les contenus validés
    public function scopeValides($query)
    {
        return $query->where('statut', 1); // 1 = Bon
    }

    // Scope pour les contenus récents
    public function scopeRecents($query, $limit = 10)
    {
        return $query->orderBy('date_creation', 'desc')->limit($limit);
    }

    // Scope pour les contenus populaires
    public function scopePopulaires($query)
    {
        return $query->withCount('views')->orderBy('views_count', 'desc');
    }

    const STATUTS = [
        'En attente',
        'Bon',
        'Médiocre',
    ];

    // Méthode pour obtenir le libellé du statut
    public function getStatutLibelleAttribute()
    {
        return self::STATUTS[$this->statut] ?? 'Inconnu';
    }

    // Méthode pour vérifier si le contenu est accessible par un utilisateur
    public function estAccessiblePar($utilisateur = null)
    {
        // Si le contenu n'est pas valide, il n'est accessible à personne
        if ($this->statut != 1) { // 1 = Bon
            return false;
        }

        // Si le contenu est gratuit, accessible à tous
        if (!$this->premium) {
            return true;
        }

        // Si le contenu est premium, vérifier si l'utilisateur l'a acheté
        if ($utilisateur && $this->estAchetePar($utilisateur->id_utilisateur)) {
            return true;
        }

        return false;
    }

    // Accesseur pour l'aperçu du texte
    public function getApercuAttribute()
    {
        $texte = strip_tags($this->texte);
        return strlen($texte) > 200 ? substr($texte, 0, 200) . '...' : $texte;
    }

    // Méthode pour obtenir la note moyenne
    public function getNoteMoyenneAttribute()
    {
        $commentairesAvecNote = $this->commentaires()->whereNotNull('note');
        
        if ($commentairesAvecNote->count() > 0) {
            return round($commentairesAvecNote->avg('note'), 1);
        }
        
        return null;
    }

    // Dans app/Models/Contenu.php, ajoutez ces méthodes :

// Accesseur pour vérifier si l'utilisateur a accès
public function getEstAccessibleAttribute()
{
    if (!$this->premium) {
        return true;
    }
    
    if (!auth()->check()) {
        return false;
    }
    
    return $this->paiements()
        ->where('id_lecteur', auth()->id())
        ->exists();
}

// Accesseur pour vérifier si l'utilisateur a aimé
public function getEstAimeParUtilisateurAttribute()
{
    if (!auth()->check()) {
        return false;
    }
    
    return $this->likes()
        ->where('id_utilisateur', auth()->id())
        ->exists();
}

// Accesseur pour vérifier si l'utilisateur a mis en favori
public function getEstFavoriDeUtilisateurAttribute()
{
    if (!auth()->check()) {
        return false;
    }
    
    return $this->favorisPar()
        ->where('favoris.id_utilisateur', auth()->id())
        ->exists();
}



}