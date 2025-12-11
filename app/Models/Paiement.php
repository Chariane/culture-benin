<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';
    protected $primaryKey = 'id_paiement';
    
    protected $fillable = [
        'id_contenu',
        'id_lecteur',
        'id_transaction',
        'date_paiement',
        'montant',
        'methode_paiement',
        'statut',
        'metadata'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relations
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    public function lecteur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_lecteur');
    }

    public function transactionPortefeuille()
    {
        return $this->hasOne(TransactionPortefeuille::class, 'reference', 'id_transaction');
    }

     public $timestamps = false;
}