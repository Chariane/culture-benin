<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueRecherche extends Model
{
    protected $table = 'historique_recherche';
    protected $primaryKey = 'id_recherche';
    
    protected $fillable = [
        'id_utilisateur',
        'terme_recherche',
        'filtres',
        'nombre_resultats',
        'date_recherche'
    ];

    protected $casts = [
        'filtres' => 'array',
        'date_recherche' => 'datetime',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Scopes
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('date_recherche', 'desc')->limit($limit);
    }

    // Méthode pour enregistrer une recherche
    public static function enregistrer($utilisateurId, $terme, $filtres = [], $nombreResultats = 0)
    {
        return self::create([
            'id_utilisateur' => $utilisateurId,
            'terme_recherche' => $terme,
            'filtres' => $filtres,
            'nombre_resultats' => $nombreResultats,
            'date_recherche' => now(),
        ]);
    }
}