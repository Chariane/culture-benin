<?php

namespace App\Http\Controllers\Auteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Contenu;
use App\Models\TypeMedia;
use App\Models\Langue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends Controller
{
    /**
     * Affichage DataTable / Vue
     */
    public function index(Request $request)
    {
        // Si requête AJAX DataTables
        if ($request->ajax()) {
            // On prépare une query (important pour pagination côté serveur)
            $query = Media::with(['contenu', 'typeMedia', 'langue'])
                ->whereHas('contenu', function ($q) {
                    $q->where('id_auteur', Auth::id());
                });

            return DataTables::of($query)
                ->addColumn('chemin', function ($row) {
                    // Affiche un lien cliquable vers le fichier s'il existe
                    if ($row->chemin) {
                        $url = Storage::disk('public')->url($row->chemin);
                        return '<a href="' . e($url) . '" target="_blank" class="text-decoration-none">'
                            . e(basename($row->chemin)) . '</a>';
                    }
                    return '—';
                })
                ->addColumn('type_media', function ($row) {
                    return $row->typeMedia->nom_media ?? '—';
                })
                ->addColumn('contenu', function ($row) {
                    return $row->contenu->titre ?? '—';
                })
                ->addColumn('langue', function ($row) {
                    return $row->langue->nom_langue ?? '—';
                })
                ->addColumn('actions', function ($row) {
                    return view('auteur.medias.actions', ['row' => $row])->render();
                })
                ->rawColumns(['chemin', 'actions'])
                ->make(true);
        }

        return view('auteur.medias.index');
    }

    /**
     * Formulaire de création (modal ou page)
     */
    public function create()
{
    $langues = Langue::all();
    $types = TypeMedia::all();
    $contenus = Contenu::where('id_auteur', Auth::id())->get();

    return view('auteur.medias.create', compact('langues', 'types', 'contenus'));
}

public function store(Request $request)
{
    $request->validate([
        'chemin' => 'required|file|max:51200|
                     mimes:jpeg,jpg,png,webp,mp3,wav,ogg,mp4,mov,avi,mkv,pdf',
        'id_contenu' => 'required|integer|exists:contenus,id_contenu',
        'id_type_media' => 'required|integer|exists:type_medias,id_type_media',
        'id_langue' => 'required|integer|exists:langues,id_langue',
    ], [
        'chemin.mimes' => 'Le fichier doit être une image, un audio, une vidéo ou un PDF.',
        'chemin.max' => 'Le fichier ne doit pas dépasser 50MB.',
    ]);

    // Vérification que le contenu appartient à l'auteur
    $contenu = Contenu::findOrFail($request->id_contenu);
    if ($contenu->id_auteur !== Auth::id()) {
        abort(403, 'Accès refusé');
    }

    // Stockage du fichier
    $path = $request->file('chemin')->store('uploads', 'public');

    Media::create([
        'chemin' => $path,
        'id_type_media' => $request->id_type_media,
        'id_contenu' => $request->id_contenu,
        'id_langue' => $request->id_langue,
    ]);

    return redirect()
        ->route('auteur.medias.index')
        ->with('success', 'Média ajouté avec succès.');
}


    /**
     * Édition - retourne le formulaire d'édition
     */
    public function edit(Media $media)
    {
        // Securité : seul l'auteur du contenu parent peut modifier
        if ($media->contenu->id_auteur !== Auth::id()) abort(403);

        $contenus = Contenu::where('id_auteur', Auth::id())->get();
        $types = TypeMedia::all();
        $langues = Langue::all();

        return view('auteur.medias.edit', compact('media', 'contenus', 'types', 'langues'));
    }

    /**
     * Mise à jour (si nouveau fichier uploadé, on remplace)
     */
    public function update(Request $request, Media $media)
{
    if ($media->contenu->id_auteur !== Auth::id()) abort(403);

    $request->validate([
        'chemin' => 'nullable|file|max:51200|
                     mimes:jpeg,jpg,png,webp,mp3,wav,ogg,mp4,mov,avi,mkv,pdf',
        'id_contenu' => 'required|integer|exists:contenus,id_contenu',
        'id_type_media' => 'required|integer|exists:type_medias,id_type_media',
        'id_langue' => 'required|integer|exists:langues,id_langue',
    ], [
        'chemin.mimes' => 'Le fichier doit être une image, un audio, une vidéo ou un PDF.',
        'chemin.max' => 'Le fichier ne doit pas dépasser 50MB.',
    ]);

    // Vérifie appartenance du contenu sélectionné
    $contenu = Contenu::findOrFail($request->id_contenu);
    if ($contenu->id_auteur !== Auth::id()) abort(403);

    // Nouveau fichier ?
    if ($request->hasFile('chemin')) {
        // suppression ancien fichier
        if ($media->chemin && Storage::disk('public')->exists($media->chemin)) {
            Storage::disk('public')->delete($media->chemin);
        }

        $media->chemin = $request->file('chemin')->store('uploads', 'public');
    }

    $media->id_contenu = $request->id_contenu;
    $media->id_type_media = $request->id_type_media;
    $media->id_langue = $request->id_langue;
    $media->save();

    return redirect()
        ->route('auteur.medias.index')
        ->with('success', 'Média mis à jour.');
}


    /**
     * Affichage d'un média
     */
    public function show(Media $media)
    {
        if ($media->contenu->id_auteur !== Auth::id()) abort(403);

        $media->load(['contenu', 'typeMedia', 'langue']);
        return view('auteur.medias.show', compact('media'));
    }

    /**
     * Suppression sécurisée (retourne JSON)
     */
    public function destroy(Media $media)
    {
        if ($media->contenu->id_auteur !== Auth::id()) {
            abort(403);
        }

        // supprimer fichier
        if ($media->chemin && Storage::disk('public')->exists($media->chemin)) {
            Storage::disk('public')->delete($media->chemin);
        }

        $media->delete();

        // si requête AJAX DataTables attend JSON
        return response()->json(['success' => true, 'message' => 'Média supprimé']);
    }
}
