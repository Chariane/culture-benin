<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';
    
    protected $fillable = [
        'id_utilisateur',
        'type_notification',
        'titre',
        'message',
        'lien',
        'est_lue',
        'date_creation',
        'date_lecture'
    ];

    protected $casts = [
        'est_lue' => 'boolean',
        'date_creation' => 'datetime',
        'date_lecture' => 'datetime',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Scopes
    public function scopeNonLues($query)
    {
        return $query->where('est_lue', false);
    }

    public function scopeLues($query)
    {
        return $query->where('est_lue', true);
    }

    public function scopeRecent($query, $limit = 20)
    {
        return $query->orderBy('date_creation', 'desc')->limit($limit);
    }

    // Méthode pour marquer comme lue
    public function marquerCommeLue()
    {
        if (!$this->est_lue) {
            $this->update([
                'est_lue' => true,
                'date_lecture' => now(),
            ]);
        }
    }

    // Méthode pour créer une notification
    public static function creer($utilisateurId, $type, $titre, $message, $lien = null)
    {
        return self::create([
            'id_utilisateur' => $utilisateurId,
            'type_notification' => $type,
            'titre' => $titre,
            'message' => $message,
            'lien' => $lien,
            'date_creation' => now(),
        ]);
    }
}