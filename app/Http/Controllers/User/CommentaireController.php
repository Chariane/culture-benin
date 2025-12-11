<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Contenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentaireController extends Controller
{
    public function index($contenuId)
    {
        $comments = Commentaire::where('id_contenu', $contenuId)
            ->with('utilisateur')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return response()->json($comments);
    }
    
    public function store(Request $request)
    {
        // Pré-traitement de la note : convertir 0 en null AVANT validation
        if ($request->note == 0) {
            $request->merge(['note' => null]);
        }
        
        $validator = Validator::make($request->all(), [
            'id_contenu' => 'required|exists:contenus,id_contenu',
            'texte' => 'required|string|min:3|max:1000',
            'note' => 'nullable|integer|min:1|max:5',
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()
                ->route('contenus.show', $request->id_contenu)
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = Auth::user();
        
        $comment = Commentaire::create([
            'id_contenu' => $request->id_contenu,
            'id_utilisateur' => $user->id_utilisateur,
            'texte' => $request->texte,
            'note' => $request->note, // Peut être null
            'date' => now(),
        ]);
        
        $comment->load('utilisateur');
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès',
                'comment' => $comment,
            ], 201);
        }
        
        // Redirection vers la page du contenu avec un message de succès
        return redirect()
            ->route('contenus.show', $request->id_contenu)
            ->with('success', 'Votre commentaire a été publié avec succès !');
    }
    
    public function update(Request $request, $id)
    {
        $comment = Commentaire::findOrFail($id);
        
        // Vérifier que l'utilisateur est l'auteur du commentaire
        if ($comment->id_utilisateur !== Auth::id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            return redirect()
                ->route('contenus.show', $comment->id_contenu)
                ->with('error', 'Non autorisé');
        }
        
        // Pré-traitement de la note : convertir 0 en null AVANT validation
        if ($request->note == 0) {
            $request->merge(['note' => null]);
        }
        
        $validator = Validator::make($request->all(), [
            'texte' => 'required|string|min:3|max:1000',
            'note' => 'nullable|integer|min:1|max:5',
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()
                ->route('contenus.show', $comment->id_contenu)
                ->withErrors($validator)
                ->withInput();
        }
        
        $comment->update([
            'texte' => $request->texte,
            'note' => $request->note,
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Commentaire mis à jour',
                'comment' => $comment,
            ]);
        }
        
        return redirect()
            ->route('contenus.show', $comment->id_contenu)
            ->with('success', 'Commentaire mis à jour avec succès !');
    }
    
    public function destroy($id, Request $request)
    {
        $comment = Commentaire::findOrFail($id);
        $contenuId = $comment->id_contenu;
        
        // Vérifier que l'utilisateur est l'auteur du commentaire
        if ($comment->id_utilisateur !== Auth::id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Non autorisé'], 403);
            }
            
            return redirect()
                ->route('contenus.show', $contenuId)
                ->with('error', 'Non autorisé');
        }
        
        $comment->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Commentaire supprimé']);
        }
        
        return redirect()
            ->route('contenus.show', $contenuId)
            ->with('success', 'Commentaire supprimé avec succès !');
    }
    
    public function report(Request $request, $id)
    {
        $comment = Commentaire::findOrFail($id);
        
        // Logique pour signaler un commentaire
        // Vous pouvez créer une table "reports" pour stocker ces signalements
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Commentaire signalé aux modérateurs']);
        }
        
        return redirect()
            ->route('contenus.show', $comment->id_contenu)
            ->with('success', 'Commentaire signalé aux modérateurs. Merci pour votre vigilance !');
    }
}