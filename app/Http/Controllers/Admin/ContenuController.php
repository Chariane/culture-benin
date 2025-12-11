<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Utilisateur;
use App\Models\TypeContenu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContenuController extends Controller
{
    public function index()
    {
        return view('admin.contenus.index');
    }

    public function create()
    {
        return view('admin.contenus.create', [
            'regions'       => Region::all(),
            'langues'       => Langue::all(),
            'types'         => TypeContenu::all(),
            'utilisateurs'  => Utilisateur::all(),
            'parents'       => Contenu::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'             => 'required|string|max:255',
            'texte'             => 'required|string',
            'statut'            => 'required|in:En attente,Bon,Médiocre',
            'parent_id'         => 'nullable|exists:contenus,id_contenu',
            'id_region'         => 'required|exists:regions,id_region',
            'id_langue'         => 'required|exists:langues,id_langue',
            'id_type_contenu'   => 'required|exists:type_contenus,id_type_contenu',
            'id_auteur'         => 'required|exists:utilisateurs,id_utilisateur',

            // modérateur requis uniquement si statut != En attente
            'id_moderateur'     => $request->statut === 'En attente'
                ? 'nullable'
                : 'required|exists:utilisateurs,id_utilisateur',

            'premium'           => 'required|in:0,1',
            'prix'              => 'nullable|required_if:premium,1|numeric|min:50',
        ]);

        // Si le contenu est "En attente", pas de modérateur
        if ($validated['statut'] === 'En attente') {
            $validated['id_moderateur'] = null;
        }

        // Date création
        $validated['date_creation'] = now();

        // Date validation uniquement pour Bon ou Médiocre
        $validated['date_validation'] =
            in_array($validated['statut'], ['Bon', 'Médiocre'])
                ? now()
                : null;

        // premium converti en bool
        $validated['premium'] = $validated['premium'] == 1;

        // si premium = 0 → prix = null
        if (! $validated['premium']) {
            $validated['prix'] = null;
        }

        Contenu::create($validated);

        return redirect()->route('admin.contenus.index')
            ->with('success', 'Contenu créé.');
    }

    public function show(Contenu $contenu)
    {
        $contenu->load('medias', 'commentaires');
        return view('admin.contenus.show', compact('contenu'));
    }

    public function edit(Contenu $contenu)
    {
        return view('admin.contenus.edit', [
            'contenu'       => $contenu,
            'regions'       => Region::all(),
            'langues'       => Langue::all(),
            'types'         => TypeContenu::all(),
            'utilisateurs'  => Utilisateur::all(),
            'parents'       => Contenu::where('id_contenu', '!=', $contenu->id_contenu)->get(),
        ]);
    }

    public function update(Request $request, Contenu $contenu)
{
    $rules = [
        'titre'            => 'required|string|max:255',
        'texte'            => 'required|string',
        'date_creation'    => 'required|date',
        'statut'           => 'required|in:En attente,Bon,Médiocre',
        'parent_id'        => 'nullable|exists:contenus,id_contenu',
        'date_validation'  => 'nullable|date',
        'id_region'        => 'required|exists:regions,id_region',
        'id_langue'        => 'required|exists:langues,id_langue',
        'id_moderateur'    => $request->statut === 'En attente'
            ? 'nullable'
            : 'required|exists:utilisateurs,id_utilisateur',
        'id_type_contenu'  => 'required|exists:type_contenus,id_type_contenu',
        // SUPPRIMER id_auteur de la validation car on garde l'auteur original
        // 'id_auteur'        => 'required|exists:utilisateurs,id_utilisateur',
        'premium'          => 'required|in:0,1',
        'prix'             => 'nullable|required_if:premium,1|numeric|min:50',
    ];

    $validated = $request->validate($rules);

    // Si statut En attente → pas de modérateur
    if ($validated['statut'] === 'En attente') {
        $validated['id_moderateur'] = null;
    }

    // date validation si Bon ou Médiocre
    if (in_array($validated['statut'], ['Bon', 'Médiocre']) && !$validated['date_validation']) {
        $validated['date_validation'] = now();
    }

    // premium en bool
    $validated['premium'] = $validated['premium'] == 1;

    // si premium=0 → prix = null
    if (! $validated['premium']) {
        $validated['prix'] = null;
    }

    // Ne pas changer l'auteur original
    // $validated['id_auteur'] = $contenu->id_auteur; // Garder l'auteur d'origine

    $contenu->update($validated);

    return redirect()->route('admin.contenus.index')
        ->with('success', 'Contenu mis à jour avec succès.');
}

    public function destroy(Contenu $contenu)
    {
        $contenu->delete();
        return back()->with('success', 'Contenu supprimé.');
    }

    public function data()
    {
        $query = Contenu::with(['type', 'langue', 'region', 'auteur', 'moderateur', 'parent']);

        return DataTables::of($query)
            ->addColumn('type', fn($c) => $c->type->nom_contenu ?? '-')
            ->addColumn('langue', fn($c) => $c->langue->nom_langue ?? '-')
            ->addColumn('region', fn($c) => $c->region->nom_region ?? '-')
            ->addColumn('auteur', fn($c) => $c->auteur->nom ?? '-')
            ->addColumn('moderateur', fn($c) => $c->moderateur->nom ?? '-')
            ->addColumn('parent', fn($c) => $c->parent->titre ?? '-')
            ->addColumn('premium', fn($c) => $c->premium ? 'Oui' : 'Non')
            ->addColumn('prix', fn($c) => $c->prix ? $c->prix . ' FCFA' : '-')
            ->addColumn('actions', fn($c) => view('admin.contenus.actions', compact('c'))->render())
            ->rawColumns(['actions'])
            ->make(true);
    }
}
