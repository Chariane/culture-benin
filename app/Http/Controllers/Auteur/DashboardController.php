<?php

namespace App\Http\Controllers\Auteur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Contenu;
use App\Models\Commentaire;
use App\Models\Media;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === STATISTIQUES CONTENUS ===
        $bons = Contenu::where('id_auteur', $user->id_utilisateur)
                        ->where('statut', 'Bon')
                        ->count();

        $mediocres = Contenu::where('id_auteur', $user->id_utilisateur)
                             ->where('statut', 'Médiocre')
                             ->count();

        $attentes = Contenu::where('id_auteur', $user->id_utilisateur)
                            ->where('statut', 'En attente')
                            ->count();


        // === COMMENTAIRES SUR LES CONTENUS DE L'AUTEUR ===
        $commentairesContenus = Commentaire::with(['utilisateur', 'contenu'])
            ->whereHas('contenu', function ($query) use ($user) {
                $query->where('id_auteur', $user->id_utilisateur);
            })
            ->latest()
            ->take(5)
            ->get();

        // === COMMENTAIRES SUR LES MÉDIAS DES CONTENUS DE L'AUTEUR ===
        // Liste des médias appartenant à l'auteur
        $mediaIds = Media::whereHas('contenu', function ($q) use ($user) {
                $q->where('id_auteur', $user->id_utilisateur);
            })
            ->pluck('id_media');

        // Commentaires liés à ces médias
        $commentairesMedias = Commentaire::with(['utilisateur', 'contenu'])
            ->whereIn('id_contenu', function ($q) use ($mediaIds) {
                $q->select('id_contenu')
                  ->from('medias')
                  ->whereIn('id_media', $mediaIds);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('auteur.dashboard', compact(
            'bons',
            'mediocres',
            'attentes',
            'commentairesContenus',
            'commentairesMedias'
        ));
    }
}
