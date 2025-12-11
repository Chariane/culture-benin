<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Le chemin vers lequel redirige Laravel après un login réussi.
     */
    public const HOME = '/admin/dashboard';

    /**
     * Configure l'enregistrement des routes de l'application.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Retirez ou commentez cette ligne si vous n'utilisez pas l'API
            // Route::middleware('api')
            //     ->prefix('api')
            //     ->group(base_path('routes/api.php'));
        });
    }
}