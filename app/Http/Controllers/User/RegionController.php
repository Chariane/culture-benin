<?php
// /home/nadege/Downloads/culture/app/Http/Controllers/User/RegionController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Contenu;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::withCount('contenus')
            ->orderBy('nom_region')
            ->paginate(12);

        return view('user.regions.index', [
            'regions' => $regions,
            'totalRegions' => Region::count(),
            'totalContents' => Contenu::where('statut', 'Bon')->count(),
        ]);
    }

    public function show($id)
    {
        $region = Region::with(['langues', 'contenus.auteur'])
            ->withCount('contenus')
            ->findOrFail($id);

        // Récupérer les contenus populaires de cette région
        $popularContents = Contenu::where('id_region', $id)
            ->where('statut', 'Bon')
            ->with(['auteur', 'type', 'medias'])
            ->withCount(['views', 'likes', 'commentaires'])
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();

        // Récupérer les contenus récents
        $recentContents = Contenu::where('id_region', $id)
            ->where('statut', 'Bon')
            ->with(['auteur', 'type', 'medias'])
            ->orderBy('date_creation', 'desc')
            ->take(12)
            ->get();

        // Statistiques
        $stats = [
            'total_contents' => $region->contenus_count,
            'languages' => $region->langues->count(),
            'authors' => $region->contenus->pluck('id_auteur')->unique()->count(),
            'premium_contents' => $region->contenus->where('premium', true)->count(),
        ];

        return view('user.regions.show', [
            'region' => $region,
            'popularContents' => $popularContents,
            'recentContents' => $recentContents,
            'stats' => $stats,
            'similarRegions' => $this->getSimilarRegions($region),
        ]);
    }

    public function contents(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        $contents = Contenu::where('id_region', $id)
            ->where('statut', 'Bon')
            ->with(['auteur', 'type', 'medias'])
            ->orderBy('date_creation', 'desc')
            ->paginate(20);

        return view('user.regions.contents', [
            'region' => $region,
            'contents' => $contents,
        ]);
    }

    private function getSimilarRegions($region)
    {
        // Récupérer les régions avec des langues similaires
        $langueIds = $region->langues->pluck('id_langue')->toArray();

        return Region::whereHas('langues', function($query) use ($langueIds) {
                $query->whereIn('id_langue', $langueIds);
            })
            ->where('id_region', '!=', $region->id_region)
            ->withCount('contenus')
            ->orderBy('contenus_count', 'desc')
            ->take(4)
            ->get();
    }

    public function getRegionStats($id)
    {
        $region = Region::findOrFail($id);

        $stats = [
            'total_contents' => $region->contenus()->count(),
            'contents_by_type' => $region->contenus()
                ->join('type_contenus', 'contenus.id_type_contenu', '=', 'type_contenus.id_type_contenu')
                ->selectRaw('type_contenus.nom_contenu, COUNT(*) as count')
                ->groupBy('type_contenus.id_type_contenu', 'type_contenus.nom_contenu')
                ->get(),
            'monthly_growth' => $this->getMonthlyGrowth($id),
            'top_authors' => $region->contenus()
                ->join('utilisateurs', 'contenus.id_auteur', '=', 'utilisateurs.id_utilisateur')
                ->selectRaw('utilisateurs.nom, utilisateurs.prenom, COUNT(*) as count')
                ->groupBy('utilisateurs.id_utilisateur', 'utilisateurs.nom', 'utilisateurs.prenom')
                ->orderBy('count', 'desc')
                ->take(5)
                ->get(),
        ];

        return response()->json($stats);
    }

    private function getMonthlyGrowth($regionId)
    {
        $growth = Contenu::where('id_region', $regionId)
            ->where('statut', 'Bon')
            ->where('date_creation', '>=', now()->subYear())
            ->selectRaw('YEAR(date_creation) as year, MONTH(date_creation) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return $growth;
    }
}