<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\TypeContenu;
use App\Models\Contenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuteurController extends Controller
{
    /**
     * Afficher la liste des auteurs.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Récupérer les types de contenu pour le layout
        $typesContenus = TypeContenu::orderBy('nom_contenu')->get();

        // Requête de base pour les auteurs : filtre par rôle (Auteur)
        $query = Utilisateur::whereHas('role', function ($query) {
            $query->where('nom', 'Auteur');
        });

        // Recherche par nom ou prénom
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('prenom', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtrer par langue
        if ($request->has('langue') && !empty($request->langue)) {
            $query->where('id_langue', $request->langue);
        }

        // Filtrer par sexe
        if ($request->has('sexe') && !empty($request->sexe)) {
            $query->where('sexe', $request->sexe);
        }

        // Compter les contenus et paginer
        $auteurs = $query->withCount('contenus')
                         ->with('langue')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);

        // Récupérer les langues disponibles pour le filtre
        $languesDisponibles = Utilisateur::whereHas('role', function ($query) {
            $query->where('nom', 'Auteur');
        })
        ->whereNotNull('id_langue')
        ->with('langue')
        ->get()
        ->pluck('langue.nom_langue', 'id_langue')
        ->unique();

        // Statistiques générales
        $stats = [
            'total_auteurs' => Utilisateur::whereHas('role', function ($query) {
                $query->where('nom', 'Auteur');
            })->count(),
            'total_contenus' => Contenu::count(),
            'auteurs_actifs' => Utilisateur::whereHas('role', function ($query) {
                $query->where('nom', 'Auteur');
            })->whereHas('contenus')->count(),
        ];

        return view('user.auteurs.index', compact(
            'auteurs', 
            'typesContenus', 
            'languesDisponibles',
            'stats'
        ));
    }

    /**
     * Afficher le profil d'un auteur spécifique.
     *
     * @param  \App\Models\Utilisateur  $auteur
     * @return \Illuminate\View\View
     */
    public function show(Utilisateur $auteur, Request $request)
    {
        // Vérifier que l'utilisateur est bien un auteur
        if (!$auteur->role || $auteur->role->nom !== 'Auteur') {
            abort(404, 'Cet utilisateur n\'est pas un auteur.');
        }

        // Récupérer les types de contenu pour le layout
        $typesContenus = TypeContenu::orderBy('nom_contenu')->get();

        // Requête pour les contenus de l'auteur
        $query = $auteur->contenus()->with(['type', 'region']);

        // Filtrer par type de contenu
        if ($request->has('type') && !empty($request->type)) {
            $query->where('id_type_contenu', $request->type);
        }

        // Filtrer par région
        if ($request->has('region') && !empty($request->region)) {
            $query->where('id_region', $request->region);
        }

        // Filtrer par date
        if ($request->has('date') && !empty($request->date)) {
            if ($request->date === 'recent') {
                $query->orderBy('date_creation', 'desc');
            } elseif ($request->date === 'ancien') {
                $query->orderBy('date_creation', 'asc');
            }
        } else {
            $query->orderBy('date_creation', 'desc');
        }

        // Paginer les résultats
        $contenus = $query->paginate(9);

        // Statistiques détaillées de l'auteur
        $stats = [
            'total_contenus' => $auteur->contenus()->count(),
            'articles' => $auteur->contenus()->where('id_type_contenu', 1)->count(),
            'photos' => $auteur->contenus()->where('id_type_contenu', 2)->count(),
            'videos' => $auteur->contenus()->where('id_type_contenu', 3)->count(),
            'regions_couvertes' => $auteur->contenus()->distinct('id_region')->count('id_region'),
            'dernier_contenu' => $auteur->contenus()->latest()->first(),
        ];

        // Récupérer les types de contenu de l'auteur pour les filtres
        $typesAuteur = $auteur->contenus()
            ->with('type')
            ->get()
            ->pluck('type.nom_contenu', 'type.id_type_contenu')
            ->unique();

        // Récupérer les régions couvertes par l'auteur
        $regionsAuteur = $auteur->contenus()
            ->with('region')
            ->whereNotNull('id_region')
            ->get()
            ->pluck('region.nom_region', 'region.id_region')
            ->unique();

        // Contenus les plus populaires (par likes)
        try {
            $contenusPopulaires = $auteur->contenus()
                ->withCount(['likes as total_likes'])
                ->orderBy('total_likes', 'desc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            $contenusPopulaires = $auteur->contenus()
                ->latest()
                ->take(3)
                ->get();
        }

        return view('user.auteurs.show', compact(
            'auteur', 
            'contenus', 
            'typesContenus', 
            'stats',
            'typesAuteur',
            'regionsAuteur',
            'contenusPopulaires'
        ));
    }

    /**
     * Recherche avancée d'auteurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = Utilisateur::whereHas('role', function ($query) {
            $query->where('nom', 'Auteur');
        });

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('prenom', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $auteurs = $query->select('id_utilisateur as id', 'nom', 'prenom', 'photo')
                         ->withCount('contenus')
                         ->take(10)
                         ->get();

        return response()->json($auteurs);
    }
}