<?php

namespace App\Http\Controllers\Auteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contenu;
use App\Models\TypeContenu;
use App\Models\Region;
use App\Models\Langue;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ContenuController extends Controller
{
    public function index()
    {
        return view('auteur.contenus.index');
    }

    public function datatable()
    {
        $query = Contenu::where('id_auteur', Auth::id())
            ->with(['type', 'region', 'langue', 'parent']);

        return DataTables::of($query)
            ->editColumn('texte', fn ($contenu) =>
                \Illuminate\Support\Str::limit(strip_tags($contenu->texte), 120)
            )
            ->addColumn('origine', fn ($contenu) => $contenu->parent->titre ?? '—')
            ->addColumn('region', fn ($contenu) => $contenu->region->nom_region ?? '—')
            ->addColumn('langue', fn ($contenu) => $contenu->langue->nom_langue ?? '—')
            ->addColumn('premium', fn ($contenu) => $contenu->premium ? 'Oui' : 'Non')
            ->addColumn('prix', fn ($contenu) => $contenu->premium ? ($contenu->prix . " F") : '—')
            ->addColumn('date_creation', fn ($contenu) => $contenu->date_creation)
            ->addColumn('actions', fn ($contenu) =>
                view('auteur.contenus.actions', compact('contenu'))->render()
            )
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('auteur.contenus.create', [
            'regions' => Region::all(),
            'langues' => Langue::all(),
            'types' => TypeContenu::all(),
            'contenusOrigine' => Contenu::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'texte' => 'required',
            'id_type_contenu' => 'nullable|exists:type_contenus,id_type_contenu',
            'id_region' => 'nullable|exists:regions,id_region',
            'id_langue' => 'nullable|exists:langues,id_langue',
            'parent_id' => 'nullable|exists:contenus,id_contenu',
            'premium' => 'required|boolean',
            'prix' => 'required_if:premium,1|numeric|min:0|max:100',
        ]);

        $now = Carbon::now()->toDateString();

        Contenu::create([
            'titre' => $request->titre,
            'texte' => $request->texte,
            'statut' => 'En attente',
            'id_type_contenu' => $request->id_type_contenu,
            'id_region' => $request->id_region,
            'id_langue' => $request->id_langue,
            'parent_id' => $request->parent_id,
            'premium' => $request->premium,
            'prix' => $request->premium ? $request->prix : null,
            'id_auteur' => Auth::id(),
            'id_moderateur' => null,
            'date_creation' => $now,
            'date_validation' => null,
        ]);

        return redirect()->route('auteur.contenus.index')
            ->with('success', 'Contenu ajouté');
    }

    public function show(Contenu $contenu)
{
    $this->authorizeAuteur($contenu);

    // Charger les relations nécessaires
    $contenu->load(['type', 'region', 'langue', 'commentaires']);

    return view('auteur.contenus.show', compact('contenu'));
}


    public function edit(Contenu $contenu)
    {
        $this->authorizeAuteur($contenu);

        return view('auteur.contenus.edit', [
            'contenu' => $contenu,
            'regions' => Region::all(),
            'langues' => Langue::all(),
            'types' => TypeContenu::all(),
            'contenusOrigine' => Contenu::where('id_contenu', '!=', $contenu->id_contenu)->get()
        ]);
    }

    public function update(Request $request, Contenu $contenu)
    {
        $this->authorizeAuteur($contenu);

        $request->validate([
            'titre' => 'required|max:255',
            'texte' => 'required',
            'id_type_contenu' => 'nullable|exists:type_contenus,id_type_contenu',
            'id_region' => 'nullable|exists:regions,id_region',
            'id_langue' => 'nullable|exists:langues,id_langue',
            'parent_id' => 'nullable|exists:contenus,id_contenu',
            'premium' => 'required|boolean',
            'prix' => 'required_if:premium,1|numeric|min:0|max:100',
        ]);

        $contenu->update([
            'titre' => $request->titre,
            'texte' => $request->texte,
            'id_type_contenu' => $request->id_type_contenu,
            'id_region' => $request->id_region,
            'id_langue' => $request->id_langue,
            'parent_id' => $request->parent_id,
            'premium' => $request->premium,
            'prix' => $request->premium ? $request->prix : null,
            'statut' => 'En attente',
            'date_validation' => null,
        ]);
        return redirect()->route('auteur.contenus.index')
            ->with('success', 'Contenu modifié');
    }

    public function destroy(Contenu $contenu)
    {
        $this->authorizeAuteur($contenu);
        $contenu->delete();

        return redirect()->route('auteur.contenus.index')
            ->with('success', 'Contenu supprimé');
    }

    private function authorizeAuteur($contenu)
    {
        if ($contenu->id_auteur !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
    }
}
