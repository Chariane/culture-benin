<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Contenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Dans app/Http\Controllers\User\LikeController.php
public function toggle(Request $request)
{
    try {
        \Log::info('=== DÉBUT TOGGLE LIKE ===');
        \Log::info('User ID: ' . Auth::id());
        \Log::info('Contenu ID: ' . $request->id_contenu);
        \Log::info('Request data:', $request->all());
        
        $request->validate([
            'id_contenu' => 'required|exists:contenus,id_contenu',
        ]);
        
        $user = Auth::user();
        $contenuId = $request->id_contenu;
        
        // Vérifier si le contenu existe
        $contenu = Contenu::find($contenuId);
        if (!$contenu) {
            \Log::error('Contenu non trouvé: ' . $contenuId);
            return response()->json([
                'success' => false,
                'message' => 'Contenu non trouvé',
            ], 404);
        }
        
        $like = Like::where('id_utilisateur', $user->id_utilisateur)
            ->where('id_contenu', $contenuId)
            ->first();
            
        if ($like) {
            $like->delete();
            $liked = false;
            $message = 'Like retiré';
            \Log::info('Like supprimé avec succès');
        } else {
            Like::create([
                'id_utilisateur' => $user->id_utilisateur,
                'id_contenu' => $contenuId,
                'date' => now(),
            ]);
            $liked = true;
            $message = 'Contenu liké avec succès';
            \Log::info('Like créé avec succès');
        }
        
        $totalLikes = Like::where('id_contenu', $contenuId)->count();
        
        \Log::info('Total likes: ' . $totalLikes);
        \Log::info('=== FIN TOGGLE LIKE ===');
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'liked' => $liked,
            'total_likes' => $totalLikes,
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur dans toggle like: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'opération',
            'error' => $e->getMessage()
        ], 500);
    }
}
    
    public function check($contenuId)
    {
        $user = Auth::user();
        
        $liked = false;
        if ($user) {
            $liked = Like::where('id_utilisateur', $user->id_utilisateur)
                ->where('id_contenu', $contenuId)
                ->exists();
        }
            
        $totalLikes = Like::where('id_contenu', $contenuId)->count();
        
        return response()->json([
            'liked' => $liked,
            'total_likes' => $totalLikes,
        ]);
    }
    
    public function userLikes()
    {
        $user = Auth::user();
        
        $likes = Like::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return response()->json($likes);
    }
    
    public function getLikers($contenuId)
    {
        $likers = Like::where('id_contenu', $contenuId)
            ->with('utilisateur')
            ->paginate(20);
            
        return response()->json($likers);
    }
}