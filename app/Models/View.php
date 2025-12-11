<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'views';
    protected $primaryKey = 'id_view';
    
    protected $fillable = [
        'id_utilisateur',
        'id_contenu', 
        'date'
    ];

    public $timestamps = false;

    // Casts
    protected $casts = [
        'date' => 'datetime',
    ];

    // Relations
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Méthode pour créer ou mettre à jour une vue
    public static function enregistrerVue($userId, $contentId)
    {
        return self::updateOrCreate(
            [
                'id_utilisateur' => $userId,
                'id_contenu' => $contentId,
            ],
            [
                'date' => now(),
            ]
        );
    }

    // Méthode pour obtenir les statistiques de vue
    public static function getStatistiquesContenu($contentId)
    {
        return self::where('id_contenu', $contentId)
            ->selectRaw('COUNT(*) as total_vues')
            ->selectRaw('COUNT(DISTINCT id_utilisateur) as utilisateurs_uniques')
            ->first();
    }
}