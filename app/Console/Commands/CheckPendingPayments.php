<?php
// app/Console/Commands/CheckPendingPayments.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paiement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    protected $signature = 'payments:check-pending';
    protected $description = 'Vérifier les paiements en attente';

    public function handle()
    {
        $pendingPayments = Paiement::where('statut', 'pending')
            ->where('created_at', '>', now()->subHours(24))
            ->get();
        
        foreach ($pendingPayments as $payment) {
            $this->checkPaymentStatus($payment);
        }
        
        $this->info('Vérification des paiements en attente terminée.');
    }
    
    private function checkPaymentStatus($payment)
    {
        try {
            $metadata = json_decode($payment->metadata, true);
            $transactionId = $payment->id_transaction ?? $metadata['transaction_id'] ?? null;
            
            if (!$transactionId) {
                Log::warning('Transaction ID manquant pour paiement', ['payment_id' => $payment->id_paiement]);
                return;
            }
            
            // Configuration FedaPay
            $environment = config('fedapay.environment', 'sandbox');
            $config = config("fedapay.{$environment}");
            $secretKey = $config['secret_key'];
            $apiUrl = $environment === 'live' 
                ? 'https://api.fedapay.com/v1'
                : 'https://sandbox-api.fedapay.com/v1';
            
            // Vérifier le statut
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Accept' => 'application/json',
            ])->get($apiUrl . '/transactions/' . $transactionId);
            
            if ($response->successful()) {
                $transaction = $response->json();
                $status = $transaction['status'] ?? null;
                
                if ($status === 'approved' && $payment->statut !== 'success') {
                    $payment->update(['statut' => 'success', 'date_paiement' => now()]);
                    Log::info('Paiement mis à jour via CRON', [
                        'payment_id' => $payment->id_paiement,
                        'transaction_id' => $transactionId,
                    ]);
                } elseif ($status === 'canceled' && $payment->statut !== 'failed') {
                    $payment->update(['statut' => 'failed']);
                    Log::info('Paiement échoué via CRON', [
                        'payment_id' => $payment->id_paiement,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur vérification paiement CRON', [
                'payment_id' => $payment->id_paiement,
                'error' => $e->getMessage(),
            ]);
        }
    }
}