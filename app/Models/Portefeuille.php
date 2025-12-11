<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portefeuille extends Model
{
    protected $table = 'portefeuilles';
    protected $primaryKey = 'id_portefeuille';
    
    protected $fillable = [
        'id_utilisateur',
        'solde',
        'devise',
        'date_creation',
        'date_modification'
    ];

    protected $casts = [
        'solde' => 'decimal:2',
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
    ];

    // Valeurs par défaut
    protected $attributes = [
        'solde' => 0.00,
        'devise' => 'XAF',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionPortefeuille::class, 'id_portefeuille');
    }

    // Méthode pour créditer le portefeuille
    public function crediter($montant, $description = 'Dépôt')
    {
        $this->solde += $montant;
        $this->save();
        
        // Enregistrer la transaction
        TransactionPortefeuille::create([
            'id_portefeuille' => $this->id_portefeuille,
            'type_transaction' => 'depot',
            'montant' => $montant,
            'description' => $description,
            'statut' => 'complete',
            'date_completion' => now(),
        ]);
        
        return $this->solde;
    }

    // Méthode pour débiter le portefeuille
    public function debiter($montant, $description = 'Retrait')
    {
        if ($this->solde >= $montant) {
            $this->solde -= $montant;
            $this->save();
            
            // Enregistrer la transaction
            TransactionPortefeuille::create([
                'id_portefeuille' => $this->id_portefeuille,
                'type_transaction' => 'retrait',
                'montant' => $montant,
                'description' => $description,
                'statut' => 'complete',
                'date_completion' => now(),
            ]);
            
            return true;
        }
        
        return false;
    }

    // Vérifier si le solde est suffisant
    public function soldeSuffisant($montant)
    {
        return $this->solde >= $montant;
    }

    // Accesseur pour le solde formaté
    public function getSoldeFormateAttribute()
    {
        return number_format($this->solde, 2, ',', ' ') . ' ' . $this->devise;
    }
}