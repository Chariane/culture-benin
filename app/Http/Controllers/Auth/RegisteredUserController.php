<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function create()
    {
        // On récupère la liste des langues pour le <select>
        $langues = Langue::orderBy('nom_langue')->get();

        return view('auth.register', compact('langues'));
    }

    /**
     * Traite l'inscription
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'password' => 'required|string|min:8|confirmed',

            // Champs optionnels
            'sexe' => 'nullable|in:Homme,Femme',

            // Langue obligatoire
            'id_langue' => 'required|integer|exists:langues,id_langue',
        ]);

        // Récupération du rôle Lecteur (par défaut)
        $roleLecteur = Role::where('nom', 'Lecteur')->firstOrFail();

        // Création de l'utilisateur
        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),

            'sexe' => $request->sexe,
            'id_langue' => $request->id_langue,

            // Valeurs par défaut
            'id_role' => $roleLecteur->id,
            'statut' => 'inactif',
            'date_inscription' => now(),
        ]);

        // Connexion automatique
        Auth::login($utilisateur);

        return redirect()->route('home');
    }
}
