<?php

return [
    'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'),
    
    'sandbox' => [
        'public_key' => env('FEDAPAY_SANDBOX_PUBLIC_KEY'),
        'secret_key' => env('FEDAPAY_SANDBOX_SECRET_KEY'),
        'checkout_url' => 'https://sandbox-checkout.fedapay.com',
        'api_url' => 'https://sandbox-api.fedapay.com/v1',
    ],
    
    'live' => [
        'public_key' => env('FEDAPAY_LIVE_PUBLIC_KEY'),
        'secret_key' => env('FEDAPAY_LIVE_SECRET_KEY'),
        'checkout_url' => 'https://checkout.fedapay.com',
        'api_url' => 'https://api.fedapay.com/v1',
    ],
    
    'currency' => 'XOF',
    
    'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET'),
];