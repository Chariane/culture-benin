<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Enregistrer un nouvel avis (depuis la page d'accueil)
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour laisser un avis.');
        }

        $request->validate([
            'message' => 'required|min:10|max:500',
        ]);

        // Vérifier si l'utilisateur n'a pas déjà laissé un avis aujourd'hui
        $todayAvis = Avis::where('id_lecteur', Auth::id())
            ->whereDate('date', today())
            ->first();

        if ($todayAvis) {
            return redirect()->route('home')
                ->with('warning', 'Vous avez déjà laissé un avis aujourd\'hui. Vous pourrez en laisser un autre demain.');
        }

        $avis = new Avis();
        $avis->id_lecteur = Auth::id();
        $avis->message = $request->message;
        $avis->date = now();
        $avis->save();

        return redirect()->route('home')
            ->with('success', 'Merci pour votre avis ! Votre commentaire a été enregistré avec succès.');
    }
}