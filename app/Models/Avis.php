<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_avis';
    protected $table = 'avis';
    
    protected $fillable = [
        'id_lecteur',
        'message',
        'date'
    ];

    // Casts pour convertir automatiquement les champs
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (lecteur)
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_lecteur', 'id_utilisateur');
    }
}