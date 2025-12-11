<?php
// app/Services/FedaPayService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedaPayService
{
    private $baseUrl;
    private $secretKey;
    private $publicKey;
    private $environment;

    public function __construct()
    {
        $this->environment = config('fedapay.environment', 'sandbox');
        $config = config("fedapay.{$this->environment}");
        
        $this->secretKey = $config['secret_key'];
        $this->publicKey = $config['public_key'];
        $this->baseUrl = $this->environment === 'live' 
            ? 'https://api.fedapay.com/v1'
            : 'https://sandbox-api.fedapay.com/v1';
    }

    public function createTransaction($data)
    {
        try {
            Log::info('Creating FedaPay transaction', [
                'data' => $data,
                'base_url' => $this->baseUrl,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withoutVerifying() // Désactive SSL en local
              ->post($this->baseUrl . '/transactions', $data);

            Log::info('FedaPay API Response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->failed()) {
                Log::error('FedaPay API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('FedaPay API Error: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FedaPay Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTransaction($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept' => 'application/json',
            ])->withoutVerifying()
              ->get($this->baseUrl . '/transactions/' . $transactionId);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FedaPay Get Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generateCheckoutUrl($transactionData)
    {
        try {
            // Étape 1: Créer la transaction
            $transaction = $this->createTransaction($transactionData);
            
            if (!isset($transaction['id'])) {
                throw new \Exception('Transaction ID non reçu de FedaPay');
            }
            
            // Étape 2: Générer le token pour le checkout
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()
              ->post($this->baseUrl . '/transactions/' . $transaction['id'] . '/token');

            if ($response->failed()) {
                throw new \Exception('Failed to generate token: ' . $response->body());
            }

            $tokenData = $response->json();
            
            if (!isset($tokenData['token'])) {
                throw new \Exception('Token non reçu de FedaPay');
            }
            
            // URL de checkout
            return $this->environment === 'live'
                ? 'https://checkout.fedapay.com/' . $tokenData['token']
                : 'https://sandbox-checkout.fedapay.com/' . $tokenData['token'];

        } catch (\Exception $e) {
            Log::error('Generate Checkout URL Error: ' . $e->getMessage());
            throw $e;
        }
    }
}