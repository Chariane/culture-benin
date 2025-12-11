<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'id_auteur' => 'required|exists:utilisateurs,id_utilisateur',
        ]);
        
        $user = Auth::user();
        $auteur = Utilisateur::findOrFail($request->id_auteur);
        
        // Vérifier que l'utilisateur ne s'abonne pas à lui-même
        if ($user->id_utilisateur === $auteur->id_utilisateur) {
            return response()->json(['error' => 'Vous ne pouvez pas vous abonner à vous-même'], 400);
        }
        
        // Vérifier si déjà abonné
        if ($user->abonnements()->where('id_auteur', $auteur->id_utilisateur)->exists()) {
            return response()->json(['error' => 'Déjà abonné à cet auteur'], 400);
        }
        
        // Créer l'abonnement
        $user->abonnements()->attach($auteur->id_utilisateur, [
            'date_abonnement' => now(),
        ]);
        
        return response()->json([
            'message' => 'Abonnement réussi',
            'auteur' => $auteur,
        ]);
    }
    
    public function unsubscribe($idAuteur)
    {
        $user = Auth::user();
        
        $user->abonnements()->detach($idAuteur);
        
        return response()->json(['message' => 'Désabonnement réussi']);
    }
    
    public function subscriptions()
    {
        $user = Auth::user();
        
        $subscriptions = $user->abonnements()
            ->withCount('contenus')
            ->paginate(20);
            
        return response()->json($subscriptions);
    }
    
    public function checkSubscription($idAuteur)
    {
        $user = Auth::user();
        
        $isSubscribed = $user->abonnements()
            ->where('id_auteur', $idAuteur)
            ->exists();
            
        return response()->json(['subscribed' => $isSubscribed]);
    }
    
    public function getSubscribers($idAuteur = null)
    {
        $user = Auth::user();
        
        // Si aucun ID fourni, utiliser l'ID de l'utilisateur connecté
        $auteurId = $idAuteur ?? $user->id_utilisateur;
        
        // Vérifier si l'utilisateur est un auteur
        $auteur = Utilisateur::findOrFail($auteurId);
        
        $subscribers = $auteur->abonnes()
            ->paginate(20);
            
        return response()->json([
            'auteur' => $auteur,
            'subscribers' => $subscribers,
            'subscriber_count' => $auteur->abonnes()->count(),
        ]);
    }
    
    public function getNewContentFromSubscriptions()
    {
        $user = Auth::user();
        
        // Récupérer les auteurs suivis
        $auteursIds = $user->abonnements()->pluck('id_auteur');
        
        // Récupérer les nouveaux contenus de ces auteurs
        $newContents = \App\Models\Contenu::whereIn('id_auteur', $auteursIds)
            ->where('statut', 'Bon')
            ->where('date_creation', '>=', now()->subDays(7))
            ->with('auteur')
            ->orderBy('date_creation', 'desc')
            ->paginate(20);
            
        return response()->json($newContents);
    }
}