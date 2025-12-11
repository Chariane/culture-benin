<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commentaire;
use App\Models\Utilisateur;
use App\Models\Contenu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CommentaireController extends Controller
{
    public function index() {
        $commentaires = Commentaire::with(['utilisateur','contenu'])->get();
        return view('admin.commentaires.index', compact('commentaires'));
    }

    public function create() {
        $utilisateurs = Utilisateur::all();
        $contenus = Contenu::all();
        return view('admin.commentaires.create', compact('utilisateurs','contenus'));
    }

    public function store(Request $request)
{
    // Pré-traitement de la note : si vide ou 0, mettre null
    if ($request->note == 0 || $request->note == '') {
        $request->merge(['note' => null]);
    }
    
    $validator = Validator::make($request->all(), [
        'id_contenu' => 'required|exists:contenus,id_contenu',
        'texte' => 'required|string|min:3|max:1000',
        'note' => 'nullable|integer|min:1|max:5',
    ]);
    
    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }
    
    $user = Auth::user();
    
    $comment = Commentaire::create([
        'id_contenu' => $request->id_contenu,
        'id_utilisateur' => $user->id_utilisateur,
        'texte' => $request->texte,
        'note' => $request->note,
        'date' => now(),
    ]);
    
    if ($request->ajax()) {
        $comment->load('utilisateur');
        return response()->json([
            'message' => 'Commentaire ajouté avec succès',
            'comment' => $comment,
        ], 201);
    } else {
        return redirect()->back()->with('success', 'Commentaire ajouté avec succès !');
    }
}

    public function show($id_commentaire)
    {
        $commentaire = Commentaire::findOrFail($id_commentaire);
        return view('admin.commentaires.show', compact('commentaire'));
    }

    public function edit(Commentaire $commentaire) {
        $utilisateurs = Utilisateur::all();
        $contenus = Contenu::all();
        return view('admin.commentaires.edit', compact('commentaire','utilisateurs','contenus'));
    }

    public function update(Request $request, Commentaire $commentaire) {
        $validated = $request->validate([
            'texte'=>'required|string',
            'note'=>'nullable|integer|min:0|max:5',
            'date'=>'nullable|date',
            'id_utilisateur'=>'required|exists:utilisateurs,id_utilisateur',
            'id_contenu'=>'required|exists:contenus,id_contenu'
        ]);

        $commentaire->update($validated);
        return redirect()->route('admin.commentaires.index')->with('success','Commentaire mis à jour.');
    }

    public function destroy(Commentaire $commentaire) {
        $commentaire->delete();
        return redirect()->route('admin.commentaires.index')->with('success','Commentaire supprimé.');
    }

    public function data()
    {
        $query = Commentaire::with(['utilisateur', 'contenu']);

        return DataTables::of($query)
            ->editColumn('texte', fn($c) => Str::limit($c->texte, 80))
            ->addColumn('utilisateur', fn($c) => $c->utilisateur->nom ?? '')
            ->addColumn('contenu', fn($c) => $c->contenu->titre ?? '')
            ->addColumn('actions', function($c){
                return view('admin.commentaires.actions', compact('c'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
