<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'favoris';
    
    // Clé primaire
    protected $primaryKey = 'id_favori';
    
    // Champs qui peuvent être remplis massivement
    protected $fillable = [
        'id_utilisateur',
        'id_contenu',
        'date_ajout'
    ];
    
    // Désactiver les timestamps automatiques (created_at, updated_at)
    public $timestamps = false;
    
    // Dates qui doivent être traitées comme des instances Carbon
    protected $dates = ['date_ajout'];
    
    // Valeurs par défaut
    protected $attributes = [
        'date_ajout' => null,
    ];
    
    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
    
    /**
     * Relation avec le contenu
     */
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu', 'id_contenu');
    }
    
    /**
     * Scope pour vérifier si un utilisateur a un contenu en favori
     */
    public function scopeWhereUserAndContent($query, $userId, $contentId)
    {
        return $query->where('id_utilisateur', $userId)
                     ->where('id_contenu', $contentId);
    }
}