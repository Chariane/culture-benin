<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\TypeContenu;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les types de contenu pour le layout
        $typesContenus = TypeContenu::orderBy('nom_contenu')->get();
        
        // Contenus récents
        $recentContents = Contenu::where('statut', 'Bon')
            ->with(['auteur', 'type', 'medias'])
            ->orderBy('date_creation', 'desc')
            ->limit(6)
            ->get();
            
        // Contenus populaires (par vues)
        $contenusPopulaires = Contenu::where('statut', 'Bon')
            ->with(['auteur', 'type', 'medias'])
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->limit(3)
            ->get();
        
        // Régions populaires - SOLUTION CORRIGÉE
        $popularRegions = Region::withCount('contenus')
            ->whereHas('contenus')  // Utiliser whereHas au lieu de having
            ->orderBy('contenus_count', 'desc')
            ->limit(6)
            ->get();
        
        // Statistiques
        $stats = [
            'contenus' => Contenu::where('statut', 'Bon')->count(),
            'regions' => Region::has('contenus')->count(),
            'auteurs' => Utilisateur::whereHas('contenus')->count(),
            'utilisateurs' => Utilisateur::count(),
        ];
        
        // Si l'utilisateur est connecté, récupérer ses favoris
        $favorisIds = [];
        if (Auth::check()) {
            $user = Auth::user();
            $favorisIds = \App\Models\Favori::where('id_utilisateur', $user->id_utilisateur)
                ->pluck('id_contenu')
                ->toArray();
            
            // Ajouter la propriété is_favorite aux contenus récents
            $recentContents->each(function ($contenu) use ($favorisIds) {
                $contenu->is_favorite = in_array($contenu->id_contenu, $favorisIds);
            });
            
            // Ajouter la propriété is_favorite aux contenus populaires
            $contenusPopulaires->each(function ($contenu) use ($favorisIds) {
                $contenu->is_favorite = in_array($contenu->id_contenu, $favorisIds);
            });
        } else {
            // Si non connecté, tous les favoris sont false
            $recentContents->each(function ($contenu) {
                $contenu->is_favorite = false;
            });
            
            $contenusPopulaires->each(function ($contenu) {
                $contenu->is_favorite = false;
            });
        }
        
        return view('user.home', compact(
            'typesContenus', 
            'recentContents', 
            'contenusPopulaires',
            'popularRegions',
            'stats'
        ));
    }

    public function dashboard()
    {
        // Votre logique dashboard existante
        $user = auth()->user();
        $recentActivity = [];
        $stats = [];

        return view('user.dashboard', compact('user', 'recentActivity', 'stats'));
    }
}