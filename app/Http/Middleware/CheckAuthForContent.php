<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthForContent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            
            // Identifier le type de route pour un message personnalisé
            $routeName = $request->route()->getName();
            
            // Messages personnalisés selon la route
            $errorMessages = [
                'contenus.show' => 'Veuillez vous connecter pour accéder à ce contenu culturel.',
                'contenus.index' => 'Veuillez vous connecter pour explorer tous les contenus.',
                'contenus.by-type' => 'Veuillez vous connecter pour accéder aux contenus de cette catégorie.',
                'contenus.by-region' => 'Veuillez vous connecter pour accéder aux contenus de cette région.',
                'contenus.by-langue' => 'Veuillez vous connecter pour accéder aux contenus dans cette langue.',
                'contenus.by-auteur' => 'Veuillez vous connecter pour accéder aux contenus de cet auteur.',
            ];
            
            $errorMessage = $errorMessages[$routeName] ?? 'Veuillez vous connecter pour accéder à cette page.';
            
            // Stocker les paramètres pour redirection après connexion
            if ($routeName === 'contenus.show') {
                $contentId = $request->route('id');
                session(['redirect_content_id' => $contentId]);
            } elseif ($routeName === 'contenus.by-type') {
                $typeId = $request->route('type');
                session(['redirect_type_id' => $typeId]);
            } elseif ($routeName === 'contenus.by-region') {
                $regionId = $request->route('region');
                session(['redirect_region_id' => $regionId]);
            }
            
            // Stocker l'URL complète pour redirection générale
            session(['redirect_after_login' => $request->fullUrl()]);
            
            return redirect()->route('login')
                ->with('error', $errorMessage)
                ->with('login_required', true);
        }

        return $next($request);
    }
}