<?php

namespace App\Http\Middleware;

use Closure;

class SetCurlOptions
{
    public function handle($request, Closure $next)
    {
        if (app()->environment('local', 'development')) {
            // Désactiver la vérification SSL pour cURL globalement
            curl_setopt_array(curl_init(), [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CONNECTTIMEOUT => 30,
            ]);
        }
        
        return $next($request);
    }
}