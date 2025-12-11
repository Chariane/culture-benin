<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPortefeuille extends Model
{
    protected $table = 'transactions_portefeuille';
    protected $primaryKey = 'id_transaction';
    
    protected $fillable = [
        'id_portefeuille',
        'type_transaction',
        'montant',
        'description',
        'reference',
        'statut',
        'date_transaction',
        'date_completion'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_transaction' => 'datetime',
        'date_completion' => 'datetime',
    ];

    // Relations
    public function portefeuille()
    {
        return $this->belongsTo(Portefeuille::class, 'id_portefeuille');
    }

    public function utilisateur()
    {
        return $this->through('portefeuille')->has('utilisateur');
    }

    // Scopes
    public function scopeComplete($query)
    {
        return $query->where('statut', 'complete');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeDepots($query)
    {
        return $query->where('type_transaction', 'depot');
    }

    public function scopeRetraits($query)
    {
        return $query->where('type_transaction', 'retrait');
    }

    public function scopeAchats($query)
    {
        return $query->where('type_transaction', 'achat');
    }

    public function scopeRecent($query, $limit = 20)
    {
        return $query->orderBy('date_transaction', 'desc')->limit($limit);
    }

    // Méthode pour compléter une transaction
    public function completer()
    {
        $this->update([
            'statut' => 'complete',
            'date_completion' => now(),
        ]);
    }

    // Méthode pour échouer une transaction
    public function echouer()
    {
        $this->update([
            'statut' => 'echoue',
            'date_completion' => now(),
        ]);
    }

    // Accesseur pour le montant formaté
    public function getMontantFormateAttribute()
    {
        $signe = in_array($this->type_transaction, ['depot', 'remboursement']) ? '+' : '-';
        return $signe . number_format($this->montant, 2, ',', ' ') . ' ' . $this->portefeuille->devise;
    }

    // Accesseur pour la couleur selon le type
    public function getCouleurTypeAttribute()
    {
        return match($this->type_transaction) {
            'depot', 'remboursement' => 'success',
            'retrait', 'achat' => 'danger',
            default => 'secondary',
        };
    }
}