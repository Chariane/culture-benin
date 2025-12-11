<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $primaryKey = 'id_commentaire';
    protected $fillable = ['texte', 'note', 'date', 'id_utilisateur', 'id_contenu'];
    
    // Casts pour convertir les types
    protected $casts = [
        'date' => 'datetime', // Ceci convertit automatiquement la string en objet Carbon
        'note' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'id_commentaire';
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }
}