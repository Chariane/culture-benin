<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Abonnement extends Pivot
{
    protected $table = 'abonnements';
    
    // La clé primaire est composite
    protected $primaryKey = null;
    public $incrementing = false;
    
    // Activer les timestamps
    public $timestamps = true;
    
    protected $fillable = [
        'id_auteur',
        'id_abonne',
        'date_abonnement',
        'notifications_actives'
    ];

    protected $casts = [
        'date_abonnement' => 'datetime',
        'notifications_actives' => 'boolean',
    ];
}