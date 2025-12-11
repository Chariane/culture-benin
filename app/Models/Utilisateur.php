<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $with=['role'];

    public function getAuthIdentifierName()
    {
        return 'id_utilisateur';
    }
    
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'sexe',
        'date_inscription',
        'date_naissance',
        'statut',
        'photo',
        'id_role',
        'id_langue',
        'google2fa_secret',
    ];

    // Pour que Laravel utilise mot_de_passe comme champ de mot de passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Cache le mot de passe et le secret 2FA
    protected $hidden = [
        'mot_de_passe',
        'google2fa_secret',
    ];

    // Si tu utilises timestamps dans la table (created_at, updated_at)
    public $timestamps = true;

    // Relations existantes
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue');
    }
    
    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_auteur', 'id_utilisateur');
    }

    /// Relations d'abonnement
    public function abonnements()
    {
        // Les auteurs que cet utilisateur suit
        return $this->belongsToMany(
            Utilisateur::class,           // Modèle cible
            'abonnements',                // Table pivot
            'id_abonne',                  // Clé étrangère pour l'abonné
            'id_auteur',                  // Clé étrangère pour l'auteur
            'id_utilisateur',             // Clé locale
            'id_utilisateur'              // Clé cible
        )
        ->withPivot('date_abonnement', 'notifications_actives')
        ->withTimestamps();  // Maintenant safe car la table a timestamps
    }

    public function abonnes()
    {
        // Les abonnés qui suivent cet utilisateur
        return $this->belongsToMany(
            Utilisateur::class,           // Modèle cible
            'abonnements',                // Table pivot
            'id_auteur',                  // Clé étrangère pour l'auteur
            'id_abonne',                  // Clé étrangère pour l'abonné
            'id_utilisateur',             // Clé locale
            'id_utilisateur'              // Clé cible
        )
        ->withPivot('date_abonnement', 'notifications_actives')
        ->withTimestamps();  // Maintenant safe car la table a timestamps
    }

    // Favoris
    public function favoris()
    {
        return $this->belongsToMany(Contenu::class, 'favoris', 'id_utilisateur', 'id_contenu')
            ->withTimestamps()
            ->withPivot('note', 'date_ajout');
    }

    // Collections personnelles
    public function collections()
    {
        return $this->hasMany(Collection::class, 'id_utilisateur');
    }

    // Notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_utilisateur');
    }

    // Préférences de notification
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class, 'id_utilisateur');
    }

    // Portefeuille
    public function portefeuille()
    {
        return $this->hasOne(Portefeuille::class, 'id_utilisateur');
    }

    // Historique des recherches
    public function historiqueRecherches()
    {
        return $this->hasMany(HistoriqueRecherche::class, 'id_utilisateur');
    }

    // Historique des vues/lectures
    public function views()
    {
        return $this->hasMany(View::class, 'id_utilisateur');
    }

    // Commentaires laissés
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_utilisateur');
    }

    // Likes donnés
    public function likes()
    {
        return $this->hasMany(Like::class, 'id_utilisateur');
    }

    // Paiements effectués (en tant que lecteur)
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_lecteur', 'id_utilisateur');
    }

    // Contenus achetés
    public function contenusAchetes()
    {
        return $this->belongsToMany(Contenu::class, 'paiements', 'id_lecteur', 'id_contenu')
            ->withTimestamps()
            ->withPivot(['id_transaction', 'date_paiement', 'montant', 'methode_paiement']);
    }

    // Méthode utilitaire pour vérifier si un contenu est acheté
    public function aAcheteContenu($contenuId)
    {
        return $this->paiements()->where('id_contenu', $contenuId)->exists();
    }

    // Méthode utilitaire pour vérifier si un utilisateur est abonné à un auteur
    public function estAbonneA($auteurId)
    {
        return $this->abonnements()->where('id_auteur', $auteurId)->exists();
    }

    // Méthode utilitaire pour vérifier si un contenu est en favoris
    public function aFavori($contenuId)
    {
        return $this->favoris()->where('id_contenu', $contenuId)->exists();
    }

    // Accesseur pour le nom complet
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Accesseur pour l'URL de la photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    // Scope pour les auteurs
    public function scopeAuteurs($query)
    {
        return $query->whereHas('role', function($q) {
            $q->where('nom_role', 'Auteur');
        });
    }

    // Scope pour les lecteurs
    public function scopeLecteurs($query)
    {
        return $query->whereHas('role', function($q) {
            $q->where('nom_role', 'Lecteur');
        });
    }

    // Dans app/Models/Utilisateur.php, ajoutez :

// Accesseur pour vérifier si l'utilisateur est abonné à cet auteur
public function getEstAbonneParUtilisateurAttribute()
{
    if (!auth()->check()) {
        return false;
    }
    
    return $this->abonnes()
        ->where('id_abonne', auth()->id())
        ->exists();
}

/**
 * Relation avec les avis laissés
 */
public function avis()
{
    return $this->hasMany(Avis::class, 'id_lecteur', 'id_utilisateur');
}

// Dans app/Models/Utilisateur.php, ajoutez :

// Relation avec les contenus modérés
public function contenusModeres()
{
    return $this->hasMany(Contenu::class, 'id_moderateur', 'id_utilisateur');
}
public function contenusValides()
{
    return $this->hasMany(Contenu::class, 'id_moderateur', 'id_utilisateur')
                ->where('statut', 1); // 1 = "Bon"
}
}