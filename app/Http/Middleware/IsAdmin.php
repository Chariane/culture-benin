<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Si pas connecté → login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si rôle différent d'Administrateur → refus
        if (Auth::user()->role->nom !== 'Administrateur') {
            abort(403, "Vous n'avez pas des privilèges pour accéder à cette page");
            redirect()->route('/');
        }

        return $next($request);
    }
}
