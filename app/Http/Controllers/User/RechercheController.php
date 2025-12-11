<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechercheController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|min:2|max:100',
            'region' => 'nullable|exists:regions,id_region',
            'langue' => 'nullable|exists:langues,id_langue',
            'type_contenu' => 'nullable|exists:type_contenus,id_type_contenu',
            'premium' => 'nullable|boolean',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'sort_by' => 'nullable|in:date_creation,titre,popularite',
            'sort_order' => 'nullable|in:asc,desc',
        ]);
        
        $query = Contenu::where('statut', 'Bon')
            ->with(['region', 'langue', 'auteur', 'type']);
        
        // Recherche par texte
        if ($request->filled('query')) {
            $searchTerm = $request->query;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('texte', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('auteur', function($q) use ($searchTerm) {
                      $q->where('nom', 'like', '%' . $searchTerm . '%')
                        ->orWhere('prenom', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Filtres
        if ($request->filled('region')) {
            $query->where('id_region', $request->region);
        }
        
        if ($request->filled('langue')) {
            $query->where('id_langue', $request->langue);
        }
        
        if ($request->filled('type_contenu')) {
            $query->where('id_type_contenu', $request->type_contenu);
        }
        
        if ($request->has('premium')) {
            $query->where('premium', $request->premium);
        }
        
        // Filtre par date
        if ($request->filled('date_from')) {
            $query->where('date_creation', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('date_creation', '<=', $request->date_to);
        }
        
        // Trier
        $sortBy = $request->get('sort_by', 'date_creation');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'popularite') {
            // Trier par popularité (nombre de vues)
            $query->withCount('views')->orderBy('views_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $results = $query->paginate($perPage);
        
        // Statistiques de recherche
        $stats = [
            'total_results' => $results->total(),
            'regions' => Region::whereIn('id_region', $results->pluck('id_region')->unique())->get(),
            'languages' => Langue::whereIn('id_langue', $results->pluck('id_langue')->unique())->get(),
        ];
        
        return response()->json([
            'results' => $results,
            'stats' => $stats,
            'search_params' => $request->all(),
        ]);
    }
    
    public function advancedSearch(Request $request)
    {
        // Recherche avancée avec plus de critères
        return $this->search($request);
    }
    
    public function searchSuggestions(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);
        
        $searchTerm = $request->query;
        
        $suggestions = [
            'contenus' => Contenu::where('statut', 'Bon')
                ->where('titre', 'like', '%' . $searchTerm . '%')
                ->select('id_contenu', 'titre', 'date_creation')
                ->take(5)
                ->get(),
            'auteurs' => \App\Models\Utilisateur::whereHas('role', function($q) {
                    $q->where('nom_role', 'Auteur');
                })
                ->where(function($q) use ($searchTerm) {
                    $q->where('nom', 'like', '%' . $searchTerm . '%')
                      ->orWhere('prenom', 'like', '%' . $searchTerm . '%');
                })
                ->select('id_utilisateur', 'nom', 'prenom', 'photo')
                ->take(5)
                ->get(),
            'regions' => Region::where('nom_region', 'like', '%' . $searchTerm . '%')
                ->select('id_region', 'nom_region')
                ->take(5)
                ->get(),
        ];
        
        return response()->json($suggestions);
    }
    
    public function searchHistory()
    {
        $user = auth()->user();
        
        // Récupérer l'historique des recherches
        // Vous devrez créer une table "search_history" pour stocker cela
        $history = []; // À implémenter
        
        return response()->json($history);
    }
    
    public function clearSearchHistory()
    {
        $user = auth()->user();
        
        // Supprimer l'historique des recherches
        // À implémenter avec votre table d'historique
        
        return response()->json(['message' => 'Historique de recherche effacé']);
    }
    
    public function getSearchFilters()
    {
        $filters = [
            'regions' => Region::all(),
            'languages' => Langue::all(),
            'content_types' => TypeContenu::all(),
            'years' => Contenu::select(DB::raw('YEAR(date_creation) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year'),
        ];
        
        return response()->json($filters);
    }
}