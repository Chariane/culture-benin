<?php

namespace App\Http\Controllers\Moderateur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Contenu;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistiques
        $en_attente = Contenu::where('statut', 'En attente')->count();

        // contenus que CE modérateur a marqué
        $valides = Contenu::where('statut', 'Bon')
                    ->where('id_moderateur', $userId)
                    ->count();

        $rejetes = Contenu::where('statut', 'Médiocre')
                    ->where('id_moderateur', $userId)
                    ->count();

        return view('moderateur.dashboard', compact(
            'en_attente',
            'valides',
            'rejetes'
        ));
    }
}
