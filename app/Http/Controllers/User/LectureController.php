<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\View;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LectureController extends Controller
{
    public function read($id)
    {
        $user = Auth::user();
        $content = Contenu::where('statut', 'Bon')
            ->with(['medias.typeMedia', 'langue'])
            ->findOrFail($id);
            
        // Vérifier l'accès premium si nécessaire
        if ($content->premium && !$this->hasPremiumAccess($content->id_contenu, $user)) {
            return response()->json(['error' => 'Accès refusé. Contenu premium.'], 403);
        }
        
        // Marquer comme en cours de lecture
        $this->markAsReading($content->id_contenu, $user);
        
        return response()->json([
            'content' => $content,
            'media_urls' => $this->getMediaUrls($content->medias),
            'reading_progress' => $this->getReadingProgress($content->id_contenu, $user),
        ]);
    }
    
    public function markAsCompleted(Request $request, $id)
    {
        $user = Auth::user();
        
        View::updateOrCreate(
            [
                'id_utilisateur' => $user->id_utilisateur,
                'id_contenu' => $id,
            ],
            [
                'date' => now(),
                'completed' => true,
            ]
        );
        
        return response()->json(['message' => 'Lecture terminée']);
    }
    
    public function history()
    {
        $user = Auth::user();
        
        $history = View::where('id_utilisateur', $user->id_utilisateur)
            ->with('contenu')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return response()->json($history);
    }
    
    public function continueReading()
    {
        $user = Auth::user();
        
        $inProgress = View::where('id_utilisateur', $user->id_utilisateur)
            ->where('completed', false)
            ->with('contenu')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
            
        return response()->json($inProgress);
    }
    
    private function hasPremiumAccess($contenuId, $user)
    {
        if (!$user) return false;
        
        return \App\Models\Paiement::where('id_contenu', $contenuId)
            ->where('id_lecteur', $user->id_utilisateur)
            ->exists();
    }
    
    private function markAsReading($contenuId, $user)
    {
        if (!$user) return;
        
        View::updateOrCreate(
            [
                'id_utilisateur' => $user->id_utilisateur,
                'id_contenu' => $contenuId,
            ],
            [
                'date' => now(),
                'last_read_at' => now(),
            ]
        );
    }
    
    private function getMediaUrls($medias)
    {
        $urls = [];
        foreach ($medias as $media) {
            $urls[] = [
                'type' => $media->typeMedia->nom_media ?? 'unknown',
                'url' => asset('storage/' . $media->chemin),
                'id' => $media->id_media,
            ];
        }
        return $urls;
    }
    
    private function getReadingProgress($contenuId, $user)
    {
        if (!$user) return 0;
        
        $view = View::where('id_utilisateur', $user->id_utilisateur)
            ->where('id_contenu', $contenuId)
            ->first();
            
        return $view ? ($view->progress ?? 0) : 0;
    }
}