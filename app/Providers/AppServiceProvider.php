<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema; // Ajoutez cette ligne
use App\Models\TypeContenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager les types de contenus avec toutes les vues
        try {
            // Vérifier si la table existe
            if (Schema::hasTable('type_contenus')) {
                $typesContenus = TypeContenu::orderBy('nom_contenu')->get();
                View::share('typesContenus', $typesContenus);
            } else {
                View::share('typesContenus', collect());
            }
        } catch (\Exception $e) {
            // En cas d'erreur, partager un tableau vide
            View::share('typesContenus', collect());
        }

        View::composer('*', function ($view) {
            $typesContenus = TypeContenu::all();
            $view->with('typesContenus', $typesContenus);
        });
        
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}