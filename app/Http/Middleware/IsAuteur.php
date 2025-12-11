<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAuteur
{
    public function handle($request, Closure $next)
    {
        // Vérifie si connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifie si son rôle est Auteur
        if (Auth::user()->role->nom !== "Auteur") {
            abort(403, "Accès refusé — réservé aux auteurs");
        }

        return $next($request);
    }
}
