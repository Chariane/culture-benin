<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';
    protected $primaryKey = 'id_collection';
    
    protected $fillable = [
        'id_utilisateur',
        'nom_collection',
        'description',
        'is_privee',
        'date_creation',
        'date_modification'
    ];

    protected $casts = [
        'is_privee' => 'boolean',
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function contenus()
    {
        return $this->belongsToMany(Contenu::class, 'collection_contenu', 'id_collection', 'id_contenu')
            ->withPivot('ordre', 'note', 'date_ajout')
            ->orderBy('collection_contenu.ordre');
    }

    // Accesseur pour le nombre de contenus
    public function getNombreContenusAttribute()
    {
        return $this->contenus()->count();
    }

    // Méthode pour ajouter un contenu
    public function ajouterContenu($contenuId, $ordre = null, $note = null)
    {
        $ordre = $ordre ?? $this->contenus()->count() + 1;
        
        if (!$this->contenus()->where('id_contenu', $contenuId)->exists()) {
            $this->contenus()->attach($contenuId, [
                'ordre' => $ordre,
                'note' => $note,
                'date_ajout' => now(),
            ]);
            
            $this->update(['date_modification' => now()]);
            
            return true;
        }
        
        return false;
    }

    // Méthode pour retirer un contenu
    public function retirerContenu($contenuId)
    {
        $this->contenus()->detach($contenuId);
        $this->update(['date_modification' => now()]);
        
        // Réorganiser l'ordre
        $this->reorganiserOrdre();
    }

    // Méthode pour réorganiser l'ordre
    public function reorganiserOrdre()
    {
        $contenus = $this->contenus()->orderBy('collection_contenu.ordre')->get();
        
        $ordre = 1;
        foreach ($contenus as $contenu) {
            $this->contenus()->updateExistingPivot($contenu->id_contenu, ['ordre' => $ordre]);
            $ordre++;
        }
    }
}