<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContenuController extends Controller
{
    // PUBLIC - Accessible sans authentification
    public function index(Request $request)
    {
        $query = Contenu::where('statut', 'Bon')
            ->with(['region', 'langue', 'auteur', 'type', 'medias']);
        
        // Filtres
        if ($request->has('region')) {
            $query->where('id_region', $request->region);
        }
        
        if ($request->has('langue')) {
            $query->where('id_langue', $request->langue);
        }
        
        if ($request->has('type')) {
            $query->where('id_type_contenu', $request->type);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('texte', 'like', '%' . $request->search . '%');
            });
        }
        
        // Premium ou non
        if ($request->has('premium')) {
            $query->where('premium', $request->premium);
        }
        
        // Trier
        $sort = $request->get('sort', 'date_creation');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        // Pagination
        $perPage = $request->get('per_page', 12);
        $contenus = $query->paginate($perPage);
        
        // Récupérer les IDs des contenus favoris si l'utilisateur est connecté
        $favorisIds = [];
        $user = Auth::user();
        
        if ($user) {
            $favorisIds = \App\Models\Favori::where('id_utilisateur', $user->id_utilisateur)
                ->pluck('id_contenu')
                ->toArray();
        }
        
        // Ajouter la propriété is_favorite à chaque contenu
        $contenus->getCollection()->transform(function ($contenu) use ($favorisIds) {
            $contenu->is_favorite = in_array($contenu->id_contenu, $favorisIds);
            return $contenu;
        });
        
        // Données pour les filtres
        $types = TypeContenu::all();
        $regions = Region::all();
        $langues = Langue::all();
        
        // Types pour le layout
        $typesContenus = $types;
        
        // Statistiques
        $stats = [
            'auteurs' => \App\Models\Utilisateur::whereHas('contenus')->count(),
            'contenus' => Contenu::where('statut', 'Bon')->count(),
        ];
        
        return view('user.contenus.index', compact(
            'contenus', 
            'types', 
            'regions', 
            'langues',
            'typesContenus',
            'stats'
        ));
    }
    
    // PUBLIC pour les contenus gratuits, PROTÉGÉ pour les contenus premium
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            $content = Contenu::where('statut', 'Bon')
                ->with(['region', 'langue', 'auteur', 'type', 'medias', 'commentaires.utilisateur'])
                ->withCount(['likes', 'views'])
                ->findOrFail($id);

            \Log::info('Contenu trouvé', [
                'id' => $content->id_contenu,
                'titre' => $content->titre,
                'premium' => $content->premium,
                'prix' => $content->prix,
            ]);

            // Vérifier si l'utilisateur a déjà liké ce contenu
            $hasLiked = false;
            if ($user) {
                $hasLiked = $content->likes()->where('id_utilisateur', $user->id_utilisateur)->exists();
            }
            
            // Vérifier si l'utilisateur a mis en favori
            $hasFavorited = false;
            if ($user) {
                $hasFavorited = \App\Models\Favori::where('id_utilisateur', $user->id_utilisateur)
                    ->where('id_contenu', $content->id_contenu)
                    ->exists();
            }
            $isSubscribed = false;
            if (auth()->check() && auth()->id() != $content->auteur->id_utilisateur) {
                $isSubscribed = auth()->user()->estAbonneA($content->auteur->id_utilisateur);
            }

            // Récupérer des contenus similaires
            $similarContents = Contenu::where('id_type_contenu', $content->id_type_contenu)
                ->where('id_contenu', '!=', $content->id_contenu)
                ->where('statut', 'Bon')
                ->with(['auteur', 'medias'])
                ->limit(4)
                ->get();
            
            // Vérifier si l'utilisateur a acheté ce contenu (statut success)
            $hasPurchased = false;
            $hasAccess = false;
            
            // Si le contenu n'est pas premium, accessible à tous
            if (!$content->premium) {
                $hasAccess = true;
                \Log::info('Contenu gratuit - accès autorisé');
                
                // Enregistrer la vue si utilisateur connecté (utiliser updateOrCreate)
                if ($user) {
                    \App\Models\View::updateOrCreate(
                        [
                            'id_utilisateur' => $user->id_utilisateur,
                            'id_contenu' => $id,
                        ],
                        [
                            'date' => now(),
                        ]
                    );
                }
            } else {
                // Contenu premium - vérifier si l'utilisateur a acheté
                if ($user) {
                    // Vérification directe de l'existence d'un enregistrement dans la table paiements avec statut success
                    $purchase = \App\Models\Paiement::where('id_contenu', $content->id_contenu)
                        ->where('id_lecteur', $user->id_utilisateur)
                        ->where('statut', 'success')
                        ->first();
                    
                    $hasPurchased = $purchase ? true : false;
                    $hasAccess = $hasPurchased;
                    
                    \Log::info('Résultat vérification achat', [
                        'hasPurchased' => $hasPurchased,
                        'user_id' => $user->id_utilisateur,
                    ]);
                    
                    if ($hasAccess) {
                        // Enregistrer la vue
                        \App\Models\View::updateOrCreate(
                            [
                                'id_utilisateur' => $user->id_utilisateur,
                                'id_contenu' => $id,
                            ],
                            [
                                'date' => now(),
                            ]
                        );
                    }
                }
            }
            
            \Log::info('Accès final', [
                'hasAccess' => $hasAccess,
                'hasPurchased' => $hasPurchased,
                'user_connecte' => $user ? 'oui' : 'non',
                'contenu_premium' => $content->premium
            ]);
            
            // Si contenu premium et pas d'accès, rediriger vers la page de paiement
            if ($content->premium && !$hasAccess) {
                // Si utilisateur non connecté, rediriger vers login
                if (!$user) {
                    return redirect()->route('login')
                        ->with('warning', 'Veuillez vous connecter pour accéder à ce contenu premium.')
                        ->with('redirect', url()->current());
                }
                
                // Rediriger vers la page de paiement avec message
                return redirect()->route('paiement.page', $content->id_contenu)
                    ->with('info', 'Ce contenu est premium. Veuillez effectuer le paiement pour y accéder.');
            }
            // Passer les variables à la vue
            return view('user.contenus.show', [
                'contenu' => $content,
                'similarContents' => $similarContents,
                'hasAccess' => $hasAccess,
                'hasPurchased' => $hasPurchased,
                'hasLiked' => $hasLiked,
                'hasFavorited' => $hasFavorited,
                'isSubscribed' => $isSubscribed,
                'likesCount' => $content->likes_count,
                'viewsCount' => $content->views_count,
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Contenu non trouvé');
        }
    }
    
    // PUBLIC - Accessible sans authentification
    public function getFilters()
    {
        return response()->json([
            'regions' => Region::all(),
            'langues' => Langue::all(),
            'types' => TypeContenu::all(),
        ]);
    }

    public function byType($typeId)
    {
        $contenus = Contenu::with(['auteur', 'medias', 'type'])
            ->where('id_type_contenu', $typeId)
            ->where('statut', 'Bon')
            ->orderBy('date_creation', 'desc')
            ->paginate(12);

        $type = TypeContenu::findOrFail($typeId);
        $types = TypeContenu::all();
        $regions = Region::all();
        $langues = Langue::all();
        $typesContenus = $types;
        
        // Statistiques
        $stats = [
            'auteurs' => \App\Models\Utilisateur::whereHas('contenus')->count(),
            'contenus' => Contenu::where('statut', 'Bon')->count(),
        ];

        // Récupérer les IDs des contenus favoris si l'utilisateur est connecté
        $favorisIds = [];
        $user = Auth::user();
        
        if ($user) {
            $favorisIds = \App\Models\Favori::where('id_utilisateur', $user->id_utilisateur)
                ->pluck('id_contenu')
                ->toArray();
        }
        
        // Ajouter la propriété is_favorite à chaque contenu
        $contenus->getCollection()->transform(function ($contenu) use ($favorisIds) {
            $contenu->is_favorite = in_array($contenu->id_contenu, $favorisIds);
            return $contenu;
        });

        return view('user.contenus.index', [
            'contenus' => $contenus,
            'currentType' => $type,
            'types' => $types,
            'regions' => $regions,
            'langues' => $langues,
            'typesContenus' => $typesContenus,
            'stats' => $stats
        ]);
    }
}