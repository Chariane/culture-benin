<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_utilisateur',
        'id_contenu',
        'date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Désactiver les timestamps automatiques si vous gérez manuellement
    public $timestamps = false;

    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
}