<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use App\Models\Contenu;
use App\Models\TypeContenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FavoriController extends Controller
{
    /**
     * Basculer l'état favori pour un contenu
     */
    public function toggle(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'id_contenu' => 'required|exists:contenus,id_contenu',
            ]);
            
            $userId = Auth::id();
            $contenuId = $request->id_contenu;
            
            Log::info("Toggle favori - User: {$userId}, Contenu: {$contenuId}");
            
            // Vérifier si le contenu existe
            $contenu = Contenu::find($contenuId);
            if (!$contenu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contenu non trouvé'
                ], 404);
            }
            
            // Vérifier si le favori existe déjà
            $existingFavori = Favori::where('id_utilisateur', $userId)
                ->where('id_contenu', $contenuId)
                ->first();
            
            if ($existingFavori) {
                // Supprimer le favori existant
                $existingFavori->delete();
                
                Log::info("Favori supprimé - ID: {$existingFavori->id_favori}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Retiré des favoris',
                    'is_favorite' => false,
                    'action' => 'removed'
                ]);
            } else {
                // Créer un nouveau favori
                $favori = new Favori();
                $favori->id_utilisateur = $userId;
                $favori->id_contenu = $contenuId;
                $favori->date_ajout = now();
                $favori->save();
                
                Log::info("Favori créé - ID: {$favori->id_favori}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Ajouté aux favoris',
                    'is_favorite' => true,
                    'action' => 'added',
                    'favori_id' => $favori->id_favori
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur toggle favori: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Vérifier si un contenu est en favori
     */
    public function check($contenuId)
    {
        try {
            $userId = Auth::id();
            
            $isFavorite = Favori::where('id_utilisateur', $userId)
                ->where('id_contenu', $contenuId)
                ->exists();
            
            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Récupérer les types de contenu pour le layout
            $typesContenus = TypeContenu::orderBy('nom_contenu')->get();
            
            // Récupérer les favoris avec pagination
            $favorisQuery = Favori::where('id_utilisateur', $user->id_utilisateur)
                ->with(['contenu' => function($query) {
                    $query->with(['type', 'region', 'auteur', 'medias']) // Changez 'typeContenu' en 'type'
                        ->where('statut', 'Bon');
                }])
                ->orderBy('date_ajout', 'desc');
            
            // Filtrer par type de contenu
            if ($request->has('type') && !empty($request->type)) {
                $favorisQuery->whereHas('contenu', function($q) use ($request) {
                    $q->where('id_type_contenu', $request->type); // Correction du nom de colonne
                });
            }
            
            // Pagination
            $favoris = $favorisQuery->paginate(12);
            
            // Statistiques
            $stats = [
                'total' => Favori::where('id_utilisateur', $user->id_utilisateur)->count(),
                'par_type' => [
                    'articles' => Favori::where('id_utilisateur', $user->id_utilisateur)
                        ->whereHas('contenu', function($q) {
                            $q->where('id_type_contenu', 1); // Correction du nom de colonne
                        })->count(),
                    'photos' => Favori::where('id_utilisateur', $user->id_utilisateur)
                        ->whereHas('contenu', function($q) {
                            $q->where('id_type_contenu', 2); // Correction du nom de colonne
                        })->count(),
                    'videos' => Favori::where('id_utilisateur', $user->id_utilisateur)
                        ->whereHas('contenu', function($q) {
                            $q->where('id_type_contenu', 3); // Correction du nom de colonne
                        })->count(),
                ]
            ];
            
            // Récupérer les types de contenu disponibles dans les favoris pour les filtres
            $typesDisponibles = TypeContenu::whereHas('contenus.favoris', function($query) use ($user) {
                $query->where('id_utilisateur', $user->id_utilisateur);
            })->get();
            
            return view('user.favoris.index', compact(
                'favoris', 
                'typesContenus', 
                'stats', 
                'typesDisponibles'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erreur affichage favoris: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Une erreur est survenue lors du chargement des favoris.');
        }
    }

}