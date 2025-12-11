<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BibliothequeController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    
    // Statistiques
    $stats = [
        'purchased_count' => Paiement::where('id_lecteur', $user->id_utilisateur)->count(),
        'saved_count' => $user->favoris()->count(),
        'collections_count' => $user->collections()->count(),
        'in_progress' => View::where('id_utilisateur', $user->id_utilisateur)
            ->where('termine', false)
            ->where('progression', '>', 0)
            ->count(),
    ];
    
    // Contenus achetés
    $purchasedContents = Paiement::where('id_lecteur', $user->id_utilisateur)
        ->with('contenu.auteur', 'contenu.type')
        ->orderBy('date_paiement', 'desc')
        ->paginate(12, ['*'], 'purchased_page');
    
    // Contenus sauvegardés
    $savedContents = $user->favoris()
        ->with('contenu.auteur', 'contenu.type')
        ->orderBy('created_at', 'desc')
        ->paginate(12, ['*'], 'saved_page');
    
    // Collections
    $collections = $user->collections()
        ->withCount('contenus')
        ->orderBy('date_creation', 'desc')
        ->get();
    
    // Contenus en cours
    $inProgressContents = View::where('id_utilisateur', $user->id_utilisateur)
        ->where('termine', false)
        ->where('progression', '>', 0)
        ->with('contenu.auteur', 'contenu.type')
        ->orderBy('derniere_lecture', 'desc')
        ->get();
    
    return view('user.bibliotheque.index', [
        'stats' => $stats,
        'purchasedContents' => $purchasedContents,
        'savedContents' => $savedContents,
        'collections' => $collections,
        'inProgressContents' => $inProgressContents,
    ]);
}
    
    public function purchasedContents(Request $request)
    {
        return $this->getPurchasedContents($request);
    }
    
    public function savedContents(Request $request)
    {
        return $this->getSavedContents($request);
    }
    
    public function createCollection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);
        
        $user = Auth::user();
        
        $collection = $user->collections()->create([
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->is_private ?? true,
        ]);
        
        return response()->json([
            'message' => 'Collection créée',
            'collection' => $collection,
        ], 201);
    }
    
    public function addToCollection(Request $request, $collectionId)
    {
        $request->validate([
            'id_contenu' => 'required|exists:contenus,id_contenu',
        ]);
        
        $user = Auth::user();
        $collection = $user->collections()->findOrFail($collectionId);
        
        // Vérifier si l'utilisateur a accès au contenu
        $contenu = Contenu::findOrFail($request->id_contenu);
        
        // Pour les contenus premium, vérifier l'achat
        if ($contenu->premium && !$this->hasPurchased($contenu->id_contenu, $user)) {
            return response()->json(['error' => 'Vous devez acheter ce contenu premium pour l\'ajouter à votre collection'], 403);
        }
        
        // Ajouter le contenu à la collection
        if (!$collection->contenus()->where('id_contenu', $contenu->id_contenu)->exists()) {
            $collection->contenus()->attach($contenu->id_contenu);
        }
        
        return response()->json(['message' => 'Contenu ajouté à la collection']);
    }
    
    public function removeFromCollection(Request $request, $collectionId)
    {
        $request->validate([
            'id_contenu' => 'required|exists:contenus,id_contenu',
        ]);
        
        $user = Auth::user();
        $collection = $user->collections()->findOrFail($collectionId);
        
        $collection->contenus()->detach($request->id_contenu);
        
        return response()->json(['message' => 'Contenu retiré de la collection']);
    }
    
    public function updateCollection(Request $request, $collectionId)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);
        
        $user = Auth::user();
        $collection = $user->collections()->findOrFail($collectionId);
        
        $collection->update($request->only(['name', 'description', 'is_private']));
        
        return response()->json([
            'message' => 'Collection mise à jour',
            'collection' => $collection,
        ]);
    }
    
    public function deleteCollection($collectionId)
    {
        $user = Auth::user();
        $collection = $user->collections()->findOrFail($collectionId);
        
        $collection->delete();
        
        return response()->json(['message' => 'Collection supprimée']);
    }
    
    public function getCollection($collectionId)
    {
        $user = Auth::user();
        $collection = $user->collections()
            ->with('contenus')
            ->findOrFail($collectionId);
            
        return response()->json($collection);
    }
    
    public function organizeShelves(Request $request)
    {
        // Logique pour organiser les étagères
        // À implémenter selon vos besoins
        
        return response()->json(['message' => 'Organisation des étagères enregistrée']);
    }
    
    private function getPurchasedContents(Request $request)
    {
        $user = Auth::user();
        
        $query = Paiement::where('id_lecteur', $user->id_utilisateur)
            ->with('contenu');
            
        if ($request->has('sort')) {
            $query->orderBy('date_paiement', $request->sort);
        } else {
            $query->orderBy('date_paiement', 'desc');
        }
        
        return $query->paginate($request->get('per_page', 20));
    }
    
    private function getSavedContents(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer les contenus sauvegardés (favoris)
        // Adaptez selon votre structure
        $query = $user->favoris();
        
        if ($request->has('sort')) {
            $query->orderBy('created_at', $request->sort);
        }
        
        return $query->paginate($request->get('per_page', 20));
    }
    
    private function getCollections($user)
    {
        // Récupérer les collections de l'utilisateur
        // Vous devrez créer un modèle Collection
        return $user->collections()->withCount('contenus')->get();
    }
    
    private function hasPurchased($contenuId, $user)
    {
        return Paiement::where('id_contenu', $contenuId)
            ->where('id_lecteur', $user->id_utilisateur)
            ->exists();
    }
}