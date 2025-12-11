<?php


// Dans routes/web.php
Route::get('/test-fedapay-keys', function() {
    try {
        $environment = config('fedapay.environment', 'sandbox');
        $config = config("fedapay.{$environment}");
        
        // Afficher les clés (partiellement pour la sécurité)
        $response = [
            'environment' => $environment,
            'public_key_exists' => !empty($config['public_key']),
            'secret_key_exists' => !empty($config['secret_key']),
            'public_key_sample' => substr($config['public_key'] ?? '', 0, 30) . '...',
            'secret_key_sample' => substr($config['secret_key'] ?? '', 0, 30) . '...',
        ];
        
        // Tester la connexion
        FedaPay::setApiKey($config['secret_key']);
        FedaPay::setEnvironment($environment);
        FedaPay::setVerifySslCerts(false);
        
        $ping = \FedaPay\Ping::retrieve();
        $response['ping_test'] = 'SUCCESS';
        $response['ping_message'] = $ping->message ?? 'Connected';
        
        // Tester la création d'un client de test
        try {
            $customer = \FedaPay\Customer::create([
                'firstname' => 'Test',
                'lastname' => 'User',
                'email' => 'test@example.com',
            ]);
            $response['customer_test'] = 'SUCCESS';
            $response['customer_id'] = $customer->id;
        } catch (\Exception $e) {
            $response['customer_test'] = 'FAILED: ' . $e->getMessage();
        }
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});