<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\View;
use App\Models\Like;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HistoriqueController extends Controller
{
    public function readingHistory(Request $request)
    {
        $user = Auth::user();
        
        $query = View::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu');
            
        // Filtrer par période
        if ($request->has('period')) {
            $period = $request->period;
            
            switch ($period) {
                case 'today':
                    $query->whereDate('date', today());
                    break;
                case 'week':
                    $query->where('date', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('date', '>=', now()->subMonth());
                    break;
                case 'year':
                    $query->where('date', '>=', now()->subYear());
                    break;
            }
        }
        
        // Filtrer par date spécifique
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        
        $history = $query->orderBy('date', 'desc')
            ->paginate($request->get('per_page', 20));
            
        return response()->json($history);
    }
    
    public function activityHistory(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer toutes les activités
        $views = View::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->selectRaw("'view' as type, id_contenu, date, id")
            ->limit(50);
            
        $likes = Like::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->selectRaw("'like' as type, id_contenu, date, id")
            ->limit(50);
            
        $comments = Commentaire::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->selectRaw("'comment' as type, id_contenu, date, id_commentaire as id")
            ->limit(50);
            
        // Fusionner toutes les activités
        $activities = $views->union($likes)->union($comments)
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return response()->json($activities);
    }
    
    public function readingProgress($contenuId)
    {
        $user = Auth::user();
        
        $view = View::where('id_utilisateur', $user->id_utilisateur)
            ->where('id_contenu', $contenuId)
            ->first();
            
        if (!$view) {
            return response()->json(['progress' => 0]);
        }
        
        return response()->json([
            'progress' => $view->progress ?? 0,
            'last_read_at' => $view->last_read_at,
            'completed' => $view->completed ?? false,
        ]);
    }
    
    public function updateReadingProgress(Request $request, $contenuId)
    {
        $request->validate([
            'progress' => 'required|numeric|min:0|max:100',
        ]);
        
        $user = Auth::user();
        
        View::updateOrCreate(
            [
                'id_utilisateur' => $user->id_utilisateur,
                'id_contenu' => $contenuId,
            ],
            [
                'progress' => $request->progress,
                'last_read_at' => now(),
                'date' => now(),
            ]
        );
        
        return response()->json(['message' => 'Progression enregistrée']);
    }
    
    public function recommendations()
    {
        $user = Auth::user();
        
        // Récupérer l'historique de lecture
        $viewedContentIds = View::where('id_utilisateur', $user->id_utilisateur)
            ->pluck('id_contenu')
            ->toArray();
            
        // Si pas d'historique, retourner du contenu populaire
        if (empty($viewedContentIds)) {
            $recommendations = \App\Models\Contenu::where('statut', 'Bon')
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->take(10)
                ->get();
                
            return response()->json($recommendations);
        }
        
        // Récupérer les contenus similaires
        // Basé sur les régions et langues des contenus consultés
        $viewedContents = \App\Models\Contenu::whereIn('id_contenu', $viewedContentIds)
            ->select('id_region', 'id_langue', 'id_type_contenu')
            ->get();
            
        $preferredRegions = $viewedContents->pluck('id_region')->filter()->unique()->toArray();
        $preferredLanguages = $viewedContents->pluck('id_langue')->filter()->unique()->toArray();
        $preferredTypes = $viewedContents->pluck('id_type_contenu')->filter()->unique()->toArray();
        
        $recommendations = \App\Models\Contenu::where('statut', 'Bon')
            ->whereNotIn('id_contenu', $viewedContentIds)
            ->where(function($query) use ($preferredRegions, $preferredLanguages, $preferredTypes) {
                if (!empty($preferredRegions)) {
                    $query->orWhereIn('id_region', $preferredRegions);
                }
                if (!empty($preferredLanguages)) {
                    $query->orWhereIn('id_langue', $preferredLanguages);
                }
                if (!empty($preferredTypes)) {
                    $query->orWhereIn('id_type_contenu', $preferredTypes);
                }
            })
            ->inRandomOrder()
            ->take(10)
            ->get();
            
        return response()->json($recommendations);
    }
    
    public function clearHistory()
    {
        $user = Auth::user();
        
        View::where('id_utilisateur', $user->id_utilisateur)->delete();
        
        return response()->json(['message' => 'Historique de lecture effacé']);
    }
    
    public function statistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_views' => View::where('id_utilisateur', $user->id_utilisateur)->count(),
            'views_today' => View::where('id_utilisateur', $user->id_utilisateur)
                ->whereDate('date', today())
                ->count(),
            'views_this_week' => View::where('id_utilisateur', $user->id_utilisateur)
                ->where('date', '>=', now()->startOfWeek())
                ->count(),
            'views_this_month' => View::where('id_utilisateur', $user->id_utilisateur)
                ->where('date', '>=', now()->startOfMonth())
                ->count(),
            'most_viewed_category' => $this->getMostViewedCategory($user),
            'reading_time' => $this->calculateReadingTime($user),
        ];
        
        return response()->json($stats);
    }
    
    private function getMostViewedCategory($user)
    {
        $mostViewed = View::where('id_utilisateur', $user->id_utilisateur)
            ->join('contenus', 'views.id_contenu', '=', 'contenus.id_contenu')
            ->join('type_contenus', 'contenus.id_type_contenu', '=', 'type_contenus.id_type_contenu')
            ->selectRaw('type_contenus.nom_contenu, COUNT(*) as count')
            ->groupBy('type_contenus.id_type_contenu', 'type_contenus.nom_contenu')
            ->orderBy('count', 'desc')
            ->first();
            
        return $mostViewed ? $mostViewed->nom_contenu : 'Aucune';
    }
    
    private function calculateReadingTime($user)
    {
        // Estimation : 200 mots par minute
        $totalWords = \App\Models\Contenu::whereIn('id_contenu', function($query) use ($user) {
                $query->select('id_contenu')
                    ->from('views')
                    ->where('id_utilisateur', $user->id_utilisateur);
            })
            ->sum(DB::raw('LENGTH(texte) - LENGTH(REPLACE(texte, " ", "")) + 1'));
            
        $minutes = ceil($totalWords / 200);
        
        return [
            'minutes' => $minutes,
            'hours' => floor($minutes / 60),
            'days' => floor($minutes / 1440),
        ];
    }
}