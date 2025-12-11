<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $table = 'notification_preferences';
    protected $primaryKey = 'id_preference';
    
    protected $fillable = [
        'id_utilisateur',
        'nouveaux_contenus',
        'commentaires',
        'likes',
        'messages',
        'marketing',
        'notifications_email',
        'notifications_push',
        'date_modification'
    ];

    protected $casts = [
        'nouveaux_contenus' => 'boolean',
        'commentaires' => 'boolean',
        'likes' => 'boolean',
        'messages' => 'boolean',
        'marketing' => 'boolean',
        'notifications_email' => 'boolean',
        'notifications_push' => 'boolean',
        'date_modification' => 'datetime',
    ];

    // Valeurs par défaut
    protected $attributes = [
        'nouveaux_contenus' => true,
        'commentaires' => true,
        'likes' => true,
        'messages' => true,
        'marketing' => false,
        'notifications_email' => true,
        'notifications_push' => true,
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Méthode pour mettre à jour les préférences
    public function mettreAJourPreferences($data)
    {
        $this->update(array_merge($data, [
            'date_modification' => now(),
        ]));
    }
}