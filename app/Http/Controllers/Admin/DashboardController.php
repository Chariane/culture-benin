<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contenu;
use App\Models\Commentaire;
use App\Models\Media;
use App\Models\Utilisateur;
use App\Models\TypeMedia;
use App\Models\TypeContenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Parler;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totaux simples
        $totalContenus     = Contenu::count();
        $totalCommentaires = Commentaire::count();
        $totalMedias       = Media::count();
        $totalUtilisateurs = Utilisateur::count();
        $totalTypeMedias   = TypeMedia::count();
        $totalTypeContenus = TypeContenu::count();
        $totalRegions      = Region::count();
        $totalLangues      = Langue::count();
        $totalParler       = Parler::count();
        $totalAvis         = Avis::count();

        // Derniers éléments
        $lastContenus = Contenu::with(['region','langue','auteur','type'])
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $lastCommentaires = Commentaire::with('utilisateur','contenu')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $lastMedias = Media::with('typeMedia','contenu')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $lastUtilisateurs = Utilisateur::orderByDesc('created_at')->take(6)->get();

        // Derniers avis
        $lastAvis = Avis::with('utilisateur')
            ->orderByDesc('date')
            ->take(8)
            ->get();

        // Contenus par statut (Bon / Médiocre ou autres)
        $contenusParStatut = Contenu::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total','statut');

        // Notes moyennes par contenu (titre -> avg)
        $notes = Commentaire::join('contenus', 'commentaires.id_contenu', '=', 'contenus.id_contenu')
            ->select('contenus.titre', DB::raw('round(avg(commentaires.note)::numeric,2) as avg_note'))
            ->groupBy('contenus.titre')
            ->orderByDesc('avg_note')
            ->limit(10)
            ->pluck('avg_note','titre');

        // Contenus par région
        $contenusParRegion = Contenu::join('regions', 'contenus.id_region', '=', 'regions.id_region')
            ->select('regions.nom_region', DB::raw('count(*) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total','nom_region');

        // Contenus par langue
        $contenusParLangue = Contenu::join('langues', 'contenus.id_langue', '=', 'langues.id_langue')
            ->select('langues.nom_langue', DB::raw('count(*) as total'))
            ->groupBy('langues.nom_langue')
            ->pluck('total','nom_langue');

        // Médias par type
        $mediasParType = Media::join('type_medias', 'medias.id_type_media', '=', 'type_medias.id_type_media')
            ->select('type_medias.nom_media', DB::raw('count(*) as total'))
            ->groupBy('type_medias.nom_media')
            ->pluck('total','nom_media');

        // Parler : nombre de relations par région / langue
        $parlerParRegion = Parler::join('regions', 'parler.id_region', '=', 'regions.id_region')
            ->select('regions.nom_region', DB::raw('count(*) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total','nom_region');

        $parlerParLangue = Parler::join('langues', 'parler.id_langue', '=', 'langues.id_langue')
            ->select('langues.nom_langue', DB::raw('count(*) as total'))
            ->groupBy('langues.nom_langue')
            ->pluck('total','nom_langue');

        // Correction pour les auteurs
        $auteurs = Utilisateur::whereHas('role', function($query) {
                $query->where('nom', 'Auteur'); 
            })
            ->withCount([
                'abonnes',
                'contenus',
                'contenus as contenus_premium_count' => function($query) {
                    $query->where('premium', true);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get(['id_utilisateur', 'nom', 'prenom', 'email', 'photo', 'created_at']);

        // Correction pour les modérateurs
        $moderateurs = Utilisateur::whereHas('role', function($query) {
                $query->where('nom', 'Modérateur'); 
            })
            ->withCount([
                'contenus as contenus_valides_count' => function($query) {
                    $query->where('statut', 1); // 1 = "Bon"
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get(['id_utilisateur', 'nom', 'prenom', 'email', 'photo', 'created_at']);

        // Calculer les performances des modérateurs
        $totalPerformance = 0;
        $countModerateursAvecTravail = 0;
        
        foreach ($moderateurs as $moderateur) {
            // Utiliser la relation définie dans le modèle
            $contenusModeres = $moderateur->contenusModeres()->count();
            $moderateur->contenus_moderes_total = $contenusModeres;
            
            // Calcul de la performance
            if ($contenusModeres > 0) {
                $performance = ($moderateur->contenus_valides_count / $contenusModeres) * 100;
                $moderateur->performance = round($performance, 1);
                $totalPerformance += $performance;
                $countModerateursAvecTravail++;
            } else {
                $moderateur->performance = 0;
            }
        }
        
        // Calculer la performance moyenne
        $performanceMoyenne = $countModerateursAvecTravail > 0 
            ? round($totalPerformance / $countModerateursAvecTravail, 1) 
            : 0;

        // Préparer la vue
        return view('admin.dashboard', [
            'totalContenus'      => $totalContenus,
            'totalCommentaires'  => $totalCommentaires,
            'totalMedias'        => $totalMedias,
            'totalUtilisateurs'  => $totalUtilisateurs,
            'totalTypeMedias'    => $totalTypeMedias,
            'totalTypeContenus'  => $totalTypeContenus,
            'totalRegions'       => $totalRegions,
            'totalLangues'       => $totalLangues,
            'totalParler'        => $totalParler,
            'totalAvis'          => $totalAvis,

            'lastContenus'       => $lastContenus,
            'lastCommentaires'   => $lastCommentaires,
            'lastMedias'         => $lastMedias,
            'lastUtilisateurs'   => $lastUtilisateurs,
            'lastAvis'           => $lastAvis,

            'contenusParStatut'  => $contenusParStatut,
            'notes'              => $notes,
            'contenusParRegion'  => $contenusParRegion,
            'contenusParLangue'  => $contenusParLangue,
            'mediasParType'      => $mediasParType,
            'parlerParRegion'    => $parlerParRegion,
            'parlerParLangue'    => $parlerParLangue,

            'auteurs'            => $auteurs,
            'moderateurs'        => $moderateurs,
            'performanceMoyenne' => $performanceMoyenne,
        ]);
    }
    
    public function search(Request $request)
    {
        $q = $request->input('q');

        // On cherche dans plusieurs tables
        $contenus = \App\Models\Contenu::where('titre', 'like', "%$q%")
            ->orWhere('description', 'like', "%$q%")
            ->get();

        $medias = \App\Models\Media::where('nom', 'like', "%$q%")->get();
        $utilisateurs = \App\Models\Utilisateur::where('nom', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->get();
        $commentaires = \App\Models\Commentaire::where('contenu', 'like', "%$q%")->get();

        return view('search.results', compact(
            'q',
            'contenus',
            'medias',
            'utilisateurs',
            'commentaires'
        ));
    }
}