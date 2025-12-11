<?php
// app/Http\Controllers/User/PaiementController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    // Méthode pour afficher la page de paiement
    public function showPaymentPage($id)
    {
        $contenu = Contenu::where('statut', 'Bon')
            ->where('premium', true)
            ->findOrFail($id);
        
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour acheter ce contenu.')
                ->with('redirect', route('paiement.page', $id));
        }
        
        // Vérifier si l'utilisateur a déjà un paiement SUCCESS pour ce contenu
        $successPurchase = Paiement::where('id_contenu', $contenu->id_contenu)
            ->where('id_lecteur', $user->id_utilisateur)
            ->where('statut', 'success')
            ->first();
        
        if ($successPurchase) {
            // Si déjà acheté, rediriger directement vers le contenu
            return redirect()->route('contenus.show', $contenu->id_contenu)
                ->with('info', 'Vous avez déjà acheté ce contenu.');
        }
        
        return view('user.paiements.payment-page', compact('contenu'));
    }
    
    // Méthode pour initier le paiement - SOLUTION 4
    public function purchase(Request $request)
    {
        $request->validate([
            'id_contenu' => 'required|exists:contenus,id_contenu',
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour effectuer un paiement.');
        }
        
        $contenu = Contenu::findOrFail($request->id_contenu);
        
        // Vérifier si l'utilisateur a déjà un paiement SUCCESS pour ce contenu
        $successPurchase = Paiement::where('id_contenu', $contenu->id_contenu)
            ->where('id_lecteur', $user->id_utilisateur)
            ->where('statut', 'success')
            ->first();
            
        if ($successPurchase) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà acheté ce contenu'
                ], 400);
            }
            
            return redirect()->route('contenus.show', $contenu->id_contenu)
                ->with('info', 'Vous avez déjà acheté ce contenu');
        }
        
        try {
            // Configuration FedaPay
            $environment = config('fedapay.environment', 'sandbox');
            $config = config("fedapay.{$environment}");
            $secretKey = $config['secret_key'];
            
            if (!$secretKey) {
                throw new \Exception('Clé API FedaPay non configurée. Vérifiez votre fichier .env');
            }
            
            $apiUrl = $environment === 'live' 
                ? 'https://api.fedapay.com/v1'
                : 'https://sandbox-api.fedapay.com/v1';
            
            // URL de callback
            $callbackUrl = route('paiement.callback');
            
            // En local, forcer HTTP
            if (app()->environment('local', 'development')) {
                $callbackUrl = str_replace('https://', 'http://', $callbackUrl);
            }
            
            // Données pour la transaction
            $transactionData = [
                'description' => 'Achat contenu: ' . $contenu->titre,
                'amount' => intval($contenu->prix), // En centimes
                'currency' => ['iso' => 'XOF'],
                'callback_url' => $callbackUrl,
                'customer' => [
                    'firstname' => $user->prenom,
                    'lastname' => $user->nom,
                    'email' => $user->email,
                ]
            ];
            
            // Ajouter téléphone si disponible
            if ($user->telephone) {
                $transactionData['customer']['phone_number'] = [
                    'number' => $user->telephone,
                    'country' => 'bj'
                ];
            }
            
            Log::info('Données transaction FedaPay', $transactionData);
            
            // 1. Créer la transaction sur FedaPay
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withoutVerifying()
              ->timeout(30)
              ->post($apiUrl . '/transactions', $transactionData);
            
            $status = $response->status();
            $body = $response->body();
            
            Log::info('Réponse FedaPay brute', ['body' => $body]);
            
            if (!$response->successful()) {
                throw new \Exception('Erreur FedaPay (HTTP ' . $status . '): ' . $body);
            }
            
            $jsonResponse = $response->json();
            
            if (!isset($jsonResponse['v1/transaction']['id'])) {
                Log::error('Structure de réponse FedaPay invalide', $jsonResponse);
                throw new \Exception('Réponse FedaPay invalide: Structure de transaction manquante');
            }
            
            $transaction = $jsonResponse['v1/transaction'];
            $transactionId = $transaction['id'];
            
            // 2. Générer le token de paiement
            Log::info('Génération token pour transaction', ['transaction_id' => $transactionId]);
            
            $tokenResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()
              ->post($apiUrl . '/transactions/' . $transactionId . '/token');
            
            if (!$tokenResponse->successful()) {
                throw new \Exception('Erreur génération token: ' . $tokenResponse->body());
            }
            
            $tokenJson = $tokenResponse->json();
            
            if (!isset($tokenJson['token'])) {
                throw new \Exception('Token de paiement non reçu');
            }
            
            $token = $tokenJson['token'];
            
            // 3. TESTER DIFFÉRENTES URLS (SOLUTION 4)
            // Basé sur les tests cURL, sandbox-process.fedapay.com fonctionne (200 OK)
            $urlsToTry = [
                'sandbox-process' => 'https://sandbox-process.fedapay.com/' . $token,
                'sandbox-checkout' => 'https://sandbox-checkout.fedapay.com/' . $token,
                // 'sandbox-pay' => 'https://sandbox-pay.fedapay.com/' . $token, // N'existe pas
            ];
            
            $checkoutUrl = null;
            $workingUrlType = null;
            
            // Tester chaque URL
            foreach ($urlsToTry as $type => $url) {
                try {
                    Log::info("Test de l'URL: {$type} - {$url}");
                    
                    // Test HEAD request rapide
                    $testResponse = Http::timeout(5)
                        ->withoutVerifying()
                        ->head($url);
                    
                    $statusCode = $testResponse->status();
                    Log::info("Statut HTTP pour {$type}: {$statusCode}");
                    
                    if ($statusCode === 200) {
                        $checkoutUrl = $url;
                        $workingUrlType = $type;
                        Log::info("URL {$type} fonctionne: {$url}");
                        break;
                    }
                } catch (\Exception $e) {
                    Log::warning("URL {$type} échoue: " . $e->getMessage());
                }
            }
            
            // Si aucune URL ne fonctionne, utiliser sandbox-process.fedapay.com (qui marche selon les tests)
            if (!$checkoutUrl) {
                $checkoutUrl = 'https://sandbox-process.fedapay.com/' . $token;
                $workingUrlType = 'sandbox-process-fallback';
                Log::warning("Aucune URL valide trouvée, utilisation de sandbox-process.fedapay.com");
            }
            
            Log::info('URL de checkout sélectionnée', [
                'checkout_url' => $checkoutUrl,
                'type' => $workingUrlType,
                'transaction_id' => $transactionId,
                'token_preview' => substr($token, 0, 20) . '...'
            ]);
            
            // 4. Créer l'enregistrement du paiement
            // 4. Créer ou mettre à jour l'enregistrement du paiement
            Paiement::updateOrCreate(
                [
                    'id_contenu' => $contenu->id_contenu,
                    'id_lecteur' => $user->id_utilisateur
                ],
                [
                    'id_transaction' => $transactionId,
                    'date_paiement' => now(),
                    'montant' => $contenu->prix,
                    'methode_paiement' => 'fedapay',
                    'statut' => 'pending',
                    'metadata' => json_encode([
                        'contenu_titre' => $contenu->titre,
                        'transaction_id' => $transactionId,
                        'checkout_url' => $checkoutUrl,
                        'token' => $token,
                        'working_url_type' => $workingUrlType,
                        'created_at' => now()->toDateTimeString(),
                        'feda_transaction' => $transaction,
                    ])
                ]
            );
            
            Log::info('Transaction créée avec succès', [
                'transaction_id' => $transactionId,
                'checkout_url' => $checkoutUrl,
                'url_type' => $workingUrlType,
            ]);
            
            // 5. Retourner la réponse
            // Détecter le type de requête
            $isAjax = $request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest';
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'checkout_url' => $checkoutUrl,
                    'transaction_id' => $transactionId,
                    'message' => 'Transaction créée avec succès',
                    'url_type' => $workingUrlType
                ]);
            }
            
            // Redirection DIRECTE vers FedaPay
            return redirect()->away($checkoutUrl);
            
        } catch (\Exception $e) {
            Log::error('Erreur paiement FedaPay: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Pour AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            // Pour formulaire normal
            return back()->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }
    
    // Méthode de paiement direct (simplifiée) - SOLUTION 4
    public function directPayment($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour acheter ce contenu.')
                ->with('redirect', route('paiement.direct', $id));
        }
        
        $contenu = Contenu::findOrFail($id);
        
        // Vérifier si déjà acheté
        $successPurchase = Paiement::where('id_contenu', $contenu->id_contenu)
            ->where('id_lecteur', $user->id_utilisateur)
            ->where('statut', 'success')
            ->first();
            
        if ($successPurchase) {
            return redirect()->route('contenus.show', $contenu->id_contenu)
                ->with('info', 'Vous avez déjà acheté ce contenu');
        }
        
        try {
            // Configuration FedaPay
            $environment = config('fedapay.environment', 'sandbox');
            $config = config("fedapay.{$environment}");
            $secretKey = $config['secret_key'];
            
            if (!$secretKey) {
                throw new \Exception('Clé API FedaPay non configurée.');
            }
            
            $apiUrl = $environment === 'live' 
                ? 'https://api.fedapay.com/v1'
                : 'https://sandbox-api.fedapay.com/v1';
            
            // URL de callback
            $callbackUrl = route('paiement.callback');
            
            // Données transaction simplifiées
            $transactionData = [
                'description' => 'Achat: ' . $contenu->titre,
                'amount' => intval($contenu->prix * 100), // En centimes
                'currency' => ['iso' => 'XOF'],
                'callback_url' => $callbackUrl,
                'customer' => [
                    'firstname' => $user->prenom,
                    'lastname' => $user->nom,
                    'email' => $user->email,
                ]
            ];
            
            // Créer la transaction
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
              ->post($apiUrl . '/transactions', $transactionData);
            
            if (!$response->successful()) {
                throw new \Exception('Erreur création transaction: ' . $response->body());
            }
            
            $transaction = $response->json()['v1/transaction'];
            $transactionId = $transaction['id'];
            
            // Générer le token
            $tokenResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->timeout(30)
              ->post($apiUrl . '/transactions/' . $transactionId . '/token');
            
            if (!$tokenResponse->successful()) {
                throw new \Exception('Erreur génération token: ' . $tokenResponse->body());
            }
            
            $token = $tokenResponse->json()['token'];
            
            // UTILISER sandbox-process.fedapay.com qui fonctionne (200 OK)
            $checkoutUrl = 'https://sandbox-process.fedapay.com/' . $token;
            
            Log::info('Paiement direct - URL utilisée', [
                'checkout_url' => $checkoutUrl,
                'transaction_id' => $transactionId
            ]);
            
            // Enregistrer le paiement
            // Enregistrer ou mettre à jour le paiement
            Paiement::updateOrCreate(
                [
                    'id_contenu' => $contenu->id_contenu,
                    'id_lecteur' => $user->id_utilisateur
                ],
                [
                    'id_transaction' => $transactionId,
                    'date_paiement' => now(),
                    'montant' => $contenu->prix,
                    'methode_paiement' => 'fedapay',
                    'statut' => 'pending',
                    'metadata' => json_encode([
                        'checkout_url' => $checkoutUrl,
                        'token' => $token,
                        'url_type' => 'sandbox-process-direct'
                    ])
                ]
            );
            
            // Redirection directe
            return redirect()->away($checkoutUrl);
            
        } catch (\Exception $e) {
            Log::error('Erreur paiement direct: ' . $e->getMessage());
            
            return redirect()->route('paiement.page', $id)
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    // Callback après paiement - VERSION CORRIGÉE
    // Callback après paiement - VERSION SIMPLIFIÉE ET FONCTIONNELLE
public function callback(Request $request)
{
    Log::info('Callback FedaPay reçu', [
        'method' => $request->method(),
        'all_params' => $request->all(),
        'query_params' => $request->query(),
        'url' => $request->fullUrl(),
    ]);

    try {
        // Récupérer l'ID de transaction selon la méthode
        if ($request->method() === 'GET') {
            // Pour les redirections GET (après paiement)
            $transactionId = $request->query('id') ?? $request->query('transaction_id');
        } else {
            // Pour les requêtes POST (webhook)
            $transactionId = $request->input('id') ?? 
                            $request->input('transaction_id') ?? 
                            $request->input('data.id');
        }

        Log::info('Transaction ID extrait', [
            'method' => $request->method(),
            'transaction_id' => $transactionId,
        ]);

        if (!$transactionId) {
            Log::error('Transaction ID manquant', $request->all());
            return redirect()->route('home')->with('error', 'Transaction ID manquant');
        }

        // Configuration FedaPay
        $environment = config('fedapay.environment', 'sandbox');
        $config = config("fedapay.{$environment}");
        $secretKey = $config['secret_key'];
        
        if (!$secretKey) {
            throw new \Exception('Clé API FedaPay non configurée');
        }

        $apiUrl = $environment === 'live' 
            ? 'https://api.fedapay.com/v1'
            : 'https://sandbox-api.fedapay.com/v1';

        // Vérifier le statut de la transaction sur FedaPay
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
            'Accept' => 'application/json',
        ])->withoutVerifying()
        ->get($apiUrl . '/transactions/' . $transactionId);

        if (!$response->successful()) {
            Log::error('Erreur vérification FedaPay', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Impossible de vérifier le statut du paiement');
        }

        $transactionData = $response->json();
        
        // Structure de réponse FedaPay
        $transaction = $transactionData['v1/transaction'] ?? $transactionData;
        $status = $transaction['status'] ?? null;

        Log::info('Statut transaction FedaPay', [
            'transaction_id' => $transactionId,
            'status' => $status,
        ]);

        if (!$status) {
            throw new \Exception('Statut de transaction non trouvé');
        }

        // Trouver le paiement correspondant
        $paiement = Paiement::where('id_transaction', $transactionId)->first();

        if (!$paiement) {
            // Chercher aussi par metadata
            $paiements = Paiement::whereIn('statut', ['pending', 'success', 'failed'])->get();
            foreach ($paiements as $pendingPayment) {
                $metadata = json_decode($pendingPayment->metadata, true) ?? [];
                if (isset($metadata['transaction_id']) && $metadata['transaction_id'] == $transactionId) {
                    $paiement = $pendingPayment;
                    break;
                }
                if (isset($metadata['feda_transaction']['id']) && $metadata['feda_transaction']['id'] == $transactionId) {
                    $paiement = $pendingPayment;
                    break;
                }
            }

            if (!$paiement) {
                throw new \Exception('Paiement non trouvé pour transaction: ' . $transactionId);
            }
        }

        Log::info('Paiement trouvé', [
            'paiement_id' => $paiement->id_paiement,
            'statut_actuel' => $paiement->statut,
            'id_contenu' => $paiement->id_contenu,
            'id_lecteur' => $paiement->id_lecteur,
        ]);

        // Vérifier si déjà success
        if ($paiement->statut === 'success') {
            Log::info('Paiement déjà en success - doublon de callback');
            return redirect()->route('contenus.show', $paiement->id_contenu)
                ->with('success', 'Paiement déjà confirmé.');
        }

        // Vérifier si l'utilisateur a déjà un paiement success pour ce contenu
        $existingSuccess = Paiement::where('id_contenu', $paiement->id_contenu)
            ->where('id_lecteur', $paiement->id_lecteur)
            ->where('statut', 'success')
            ->where('id_paiement', '!=', $paiement->id_paiement)
            ->exists();

        if ($existingSuccess) {
            $paiement->update([
                'statut' => 'failed',
                'date_paiement' => now(),
                'metadata' => json_encode(array_merge(
                    json_decode($paiement->metadata, true) ?? [],
                    [
                        'callback_received_at' => now()->toDateTimeString(),
                        'transaction_status' => $status,
                        'full_transaction_data' => $transaction,
                        'duplicate_payment' => true,
                    ]
                )),
            ]);

            Log::info('Paiement en doublon détecté et marqué failed');
            
            return redirect()->route('contenus.show', $paiement->id_contenu)
                ->with('info', 'Vous avez déjà acheté ce contenu.');
        }

        // Mettre à jour le statut selon le statut FedaPay
        $newStatus = 'pending';
        $message = 'Paiement en attente';

        if ($status === 'approved') {
            $newStatus = 'success';
            $message = 'Paiement effectué avec succès !';
            
            // Marquer tous les autres paiements en pending comme failed
            Paiement::where('id_contenu', $paiement->id_contenu)
                ->where('id_lecteur', $paiement->id_lecteur)
                ->where('statut', 'pending')
                ->where('id_paiement', '!=', $paiement->id_paiement)
                ->update(['statut' => 'failed']);
                
        } elseif (in_array($status, ['canceled', 'declined', 'failed'])) {
            $newStatus = 'failed';
            $message = 'Paiement échoué ou annulé';
        }

        // Mettre à jour le paiement
        $paiement->update([
            'statut' => $newStatus,
            'date_paiement' => now(),
            'metadata' => json_encode(array_merge(
                json_decode($paiement->metadata, true) ?? [],
                [
                    'callback_received_at' => now()->toDateTimeString(),
                    'transaction_status' => $status,
                    'full_transaction_data' => $transaction,
                    'callback_method' => $request->method(),
                    'callback_data' => $request->all(),
                ]
            )),
        ]);

        Log::info('Paiement mis à jour', [
            'paiement_id' => $paiement->id_paiement,
            'ancien_statut' => $paiement->getOriginal('statut'),
            'nouveau_statut' => $newStatus,
        ]);

        // Rediriger avec message approprié
        if ($newStatus === 'success') {
            return redirect()->route('contenus.show', $paiement->id_contenu)
                ->with('success', $message);
        } elseif ($newStatus === 'failed') {
            return redirect()->route('paiement.page', $paiement->id_contenu)
                ->with('error', $message);
        } else {
            return redirect()->route('paiement.verify', $transactionId)
                ->with('warning', 'Paiement en attente de confirmation');
        }

    } catch (\Exception $e) {
        Log::error('Erreur callback FedaPay: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        return redirect()->route('contenus.index')
            ->with('error', 'Erreur lors du traitement du paiement: ' . $e->getMessage());
    }
}
        
        // Méthode pour vérifier un paiement en attente
        public function verifyPayment($transactionId)
        {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')
                    ->with('warning', 'Veuillez vous connecter pour vérifier votre paiement.');
            }
            
            $paiement = Paiement::where('id_transaction', $transactionId)
                ->where('id_lecteur', $user->id_utilisateur)
                ->firstOrFail();
            
            // Vérifier si le paiement est déjà success
            if ($paiement->statut === 'success') {
                return redirect()->route('contenus.show', $paiement->id_contenu)
                    ->with('info', 'Votre paiement a déjà été confirmé.');
            }
            
            // Vérifier le statut sur FedaPay
            try {
                $environment = config('fedapay.environment', 'sandbox');
                $config = config("fedapay.{$environment}");
                $secretKey = $config['secret_key'];
                $apiUrl = $environment === 'live' 
                    ? 'https://api.fedapay.com/v1'
                    : 'https://sandbox-api.fedapay.com/v1';
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                    'Accept' => 'application/json',
                ])->withoutVerifying()
                ->get($apiUrl . '/transactions/' . $transactionId);
                
                if ($response->successful()) {
                    $transaction = $response->json();
                    $status = $transaction['status'] ?? null;
                    
                    // Mettre à jour le statut
                    if ($status === 'approved') {
                        // Vérifier s'il existe déjà un paiement success
                        $existingSuccess = Paiement::where('id_contenu', $paiement->id_contenu)
                            ->where('id_lecteur', $user->id_utilisateur)
                            ->where('statut', 'success')
                            ->where('id_paiement', '!=', $paiement->id_paiement)
                            ->exists();
                        
                        if ($existingSuccess) {
                            $paiement->update(['statut' => 'failed']);
                            return redirect()->route('contenus.show', $paiement->id_contenu)
                                ->with('info', 'Vous avez déjà acheté ce contenu.');
                        }
                        
                        $paiement->update(['statut' => 'success']);
                        
                        return redirect()->route('contenus.show', $paiement->id_contenu)
                            ->with('success', 'Paiement confirmé ! Vous pouvez maintenant accéder au contenu.');
                    }
                }
                
                return view('user.paiements.verify', [
                    'paiement' => $paiement,
                    'contenu' => $paiement->contenu,
                ]);
                
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de la vérification: ' . $e->getMessage());
            }
        }
    
    // Webhook pour les notifications FedaPay (optionnel)
    public function webhook(Request $request)
    {
        Log::info('Webhook FedaPay reçu (mode développement)', $request->all());
        
        // En développement, on accepte tout sans vérification
        if (app()->environment('local', 'development')) {
            $payload = $request->all();
            
            if (isset($payload['data']['id'])) {
                $transactionId = $payload['data']['id'];
                $eventType = $payload['type'] ?? 'unknown';
                
                Log::info('Événement webhook FedaPay', [
                    'transaction_id' => $transactionId,
                    'event_type' => $eventType,
                ]);
                
                // Traiter l'événement selon le type
                if ($eventType === 'transaction.approved') {
                    $paiement = Paiement::where('id_transaction', $transactionId)->first();
                    if ($paiement && $paiement->statut !== 'success') {
                        // Vérifier s'il existe déjà un paiement success
                        $existingSuccess = Paiement::where('id_contenu', $paiement->id_contenu)
                            ->where('id_lecteur', $paiement->id_lecteur)
                            ->where('statut', 'success')
                            ->where('id_paiement', '!=', $paiement->id_paiement)
                            ->exists();
                        
                        if ($existingSuccess) {
                            $paiement->update(['statut' => 'failed']);
                            Log::info('Paiement en doublon rejeté via webhook', [
                                'paiement_id' => $paiement->id_paiement,
                            ]);
                        } else {
                            $paiement->update([
                                'statut' => 'success',
                                'date_paiement' => now(),
                                'metadata' => json_encode(array_merge(
                                    json_decode($paiement->metadata, true),
                                    [
                                        'webhook_received_at' => now()->toDateTimeString(),
                                        'webhook_event' => $payload,
                                    ]
                                )),
                            ]);
                            Log::info('Paiement mis à jour via webhook', [
                                'paiement_id' => $paiement->id_paiement,
                                'transaction_id' => $transactionId,
                            ]);
                        }
                    }
                }
            }
        }
        
        return response()->json(['status' => 'received']);
    }
    
    // Historique des achats
    public function purchaseHistory()
    {
        $user = Auth::user();
        
        $purchases = Paiement::where('id_lecteur', $user->id_utilisateur)
            ->with('contenu')
            ->orderBy('date_paiement', 'desc')
            ->paginate(20);
            
        return view('user.paiements.history', compact('purchases'));
    }
}