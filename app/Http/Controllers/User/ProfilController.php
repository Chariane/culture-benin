<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load(['role', 'langue']);
        
        // Déterminer l'URL de la photo (par défaut ou personnelle)
        $photoUrl = $this->getUserPhotoUrl($user);
        
        // Récupérer toutes les langues pour le formulaire
        $langues = Langue::all();
        
        // Statistiques
        $stats = [
            'contenus_lus' => \App\Models\View::where('id_utilisateur', $user->id_utilisateur)->count(),
            'commentaires' => \App\Models\Commentaire::where('id_utilisateur', $user->id_utilisateur)->count(),
            'likes' => \App\Models\Like::where('id_utilisateur', $user->id_utilisateur)->count(),
            'achats' => \App\Models\Paiement::where('id_lecteur', $user->id_utilisateur)->count(),
        ];
        
        // Dernières activités
        $activites = collect();
        
        $views = \App\Models\View::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($view) {
                $view->type = 'view';
                return $view;
            });
            
        $likes = \App\Models\Like::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($like) {
                $like->type = 'like';
                return $like;
            });
            
        $comments = \App\Models\Commentaire::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($comment) {
                $comment->type = 'comment';
                return $comment;
            });
        
        $activites = $views->merge($likes)->merge($comments)
            ->sortByDesc('created_at')
            ->take(5);
        
        return view('user.profil.show', compact('user', 'photoUrl', 'stats', 'activites', 'langues'));
    }
    
    // Dans ProfilController.php
private function getUserPhotoUrl($user)
{
    // Vérifier si l'utilisateur a une photo personnelle
    if ($user->photo) {
        $photoPath = 'public/' . $user->photo;
        // Vérifier si le fichier existe vraiment
        if (Storage::exists($photoPath)) {
            return Storage::url($user->photo);
        }
    }
    
    // Photo par défaut selon le sexe
    if ($user->sexe == 'Femme') {
        return asset('female.jpg');
    } elseif ($user->sexe == 'Homme') {
        return asset('male.jpg');
    } else {
        // Photo neutre par défaut
        return asset('images/default-avatar.jpg');
    }
}
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $user->id_utilisateur . ',id_utilisateur',
            'sexe' => 'sometimes|in:Homme,Femme,Autre',
            'date_naissance' => 'sometimes|date|nullable',
            'id_langue' => 'sometimes|exists:langues,id_langue|nullable',
            'bio' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Mettre à jour les informations de base
        $user->update($request->only([
            'nom', 'prenom', 'email', 'sexe', 'date_naissance', 'id_langue', 'bio'
        ]));
        
        return redirect()->route('profil.show')
            ->with('success', 'Profil mis à jour avec succès');
    }
    
    public function security()
    {
        $user = Auth::user();
        $photoUrl = $this->getUserPhotoUrl($user);
        
        return view('user.profil.security', compact('user', 'photoUrl'));
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = Auth::user();
        
        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->mot_de_passe)) {
            return redirect()->back()
                ->with('error', 'Le mot de passe actuel est incorrect')
                ->withInput();
        }
        
        // Mettre à jour le mot de passe
        $user->update([
            'mot_de_passe' => Hash::make($request->new_password),
        ]);
        
        return redirect()->route('profil.security')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }
    
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        $user = Auth::user();
        
        // Supprimer l'ancienne photo si elle existe
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
        }
        
        // Enregistrer la nouvelle photo
        $path = $request->file('photo')->store('photos', 'public');
        
        $user->update(['photo' => $path]);
        
        return redirect()->route('profil.show')
            ->with('success', 'Photo de profil mise à jour avec succès');
    }
    
    public function activity()
    {
        $user = Auth::user();
        $photoUrl = $this->getUserPhotoUrl($user);
        
        // Vues récentes
        $views = \App\Models\View::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'views_page');
            
        // Commentaires récents
        $comments = \App\Models\Commentaire::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'comments_page');
            
        // Likes récents
        $likes = \App\Models\Like::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'likes_page');
            
        // Achats récents
        $purchases = \App\Models\Paiement::where('id_lecteur', $user->id_utilisateur)
            ->with('contenu')
            ->orderBy('date_paiement', 'desc')
            ->paginate(10, ['*'], 'purchases_page');
        
        return view('user.profil.activity', compact('user', 'photoUrl', 'views', 'comments', 'likes', 'purchases'));
    }
    
    public function notifications()
    {
        $user = Auth::user();
        $photoUrl = $this->getUserPhotoUrl($user);
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('user.profil.notifications', compact('user', 'photoUrl', 'notifications'));
    }
}