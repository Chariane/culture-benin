<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Models\TypeMedia;
use App\Models\Contenu;
use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends Controller
{
    public function index()
    {
        // On ne charge plus les données ici, DataTables le fera via AJAX
        return view('admin.medias.index');
    }

    public function create()
    {
        $typeMedias = TypeMedia::all();
        $contenus = Contenu::all();
        $langues = Langue::all(); 

        return view('admin.medias.create', compact('typeMedias', 'contenus', 'langues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chemin' => 'required|file|mimes:jpg,jpeg,png,gif,html,pdf,mp4|max:20480',
            'id_type_media' => 'required|exists:type_medias,id_type_media',
            'id_contenu' => 'required|exists:contenus,id_contenu',
            'id_langue' => 'required|exists:langues,id_langue',
        ]);

        $path = $request->file('chemin')->store('uploads', 'public');
        $validated['chemin'] = $path;

        Media::create($validated);

        return redirect()->route('admin.medias.index')->with('success', 'Media créé avec succès.');
    }

    public function show(Media $media)
    {
        return view('admin.medias.show', compact('media'));
    }

    public function edit(Media $media)
    {
        $typeMedias = TypeMedia::all();
        $contenus = Contenu::all();
        $langues = Langue::all();

        return view('admin.medias.edit', [
            'media' => $media,
            'typeMedias' => $typeMedias,
            'contenus' => $contenus,
            'langues' => $langues
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'chemin' => 'nullable|file|mimes:jpg,jpeg,png,gif,html,pdf,mp4|max:10240',
            'id_type_media' => 'required|exists:type_medias,id_type_media',
            'id_contenu' => 'required|exists:contenus,id_contenu',
        ]);

        if ($request->hasFile('chemin')) {
            Storage::disk('public')->delete($media->chemin);  
            $validated['chemin'] = $request->file('chemin')->store('uploads', 'public');
        }

        $media->update($validated);

        return redirect()->route('admin.medias.index')->with('success', 'Media mis à jour.');
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->chemin);
        $media->delete();

        return redirect()->route('admin.medias.index')->with('success', 'Media supprimé.');
    }

    public function data(Request $request)
    {
        // Vérifier que la requête est bien une requête AJAX de DataTables
        if ($request->ajax()) {
            try {
                $medias = Media::with(['typeMedia'])->select('medias.*');

                return DataTables::eloquent($medias)
                    ->addColumn('type', function ($media) {
                        return $media->typeMedia->nom_media ?? '—';
                    })
                    ->addColumn('actions', function ($media) {
                        return view('admin.medias.actions', compact('media'))->render();
                    })
                    ->rawColumns(['actions'])
                    ->toJson();
            } catch (\Exception $e) {
                // Log l'erreur
                \Log::error('Erreur DataTables Media: ' . $e->getMessage());

                // Retourner une réponse d'erreur au format JSON que DataTables peut comprendre
                return response()->json([
                    'draw' => $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Une erreur est survenue lors du chargement des données.'
                ], 500);
            }
        }
        
        // Si ce n'est pas une requête AJAX, on renvoie une erreur 404
        abort(404);
    }
}