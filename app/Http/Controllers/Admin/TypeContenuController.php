<?php

namespace App\Http\Controllers\Admin;

use App\Models\TypeContenu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class TypeContenuController extends Controller
{
    public function index()
    {
        $typeContenus = TypeContenu::all();
        return view('admin.type_contenus.index', compact('typeContenus'));
    }

    public function create()
    {
        return view('admin.type_contenus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_contenu' => 'required|string|max:255'
        ]);

        TypeContenu::create($validated);

        return redirect()->route('admin.type_contenus.index')
            ->with('success', 'Type créé.');
    }

    public function show(TypeContenu $typeContenu)
    {
        return view('admin.type_contenus.show', compact('typeContenu'));
    }

    public function edit(TypeContenu $typeContenu)
    {
        return view('admin.type_contenus.edit', compact('typeContenu'));
    }

    public function update(Request $request, TypeContenu $typeContenu)
    {
        $validated = $request->validate([
            'nom_contenu' => 'required|string|max:255'
        ]);

        $typeContenu->update($validated);

        return redirect()->route('admin.type_contenus.index')
            ->with('success', 'Type mis à jour.');
    }

   public function destroy(TypeContenu $typeContenu)
    {
        try {
            $typeContenu->delete();

            return redirect()
                ->route('admin.type_contenus.index')
                ->with('success', 'Type de contenu supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            // Code PostgreSQL : violation FK
            if ($e->getCode() == "23503") {
                return redirect()
                    ->route('admin.type_contenus.index')
                    ->with('error', 'Impossible de supprimer ce type de contenu car des contenus y sont rattachés.');
            }

            return redirect()
                ->route('admin.type_contenus.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }



    public function data()
    {
        return DataTables::of(TypeContenu::query())
            ->addColumn('actions', function ($t) {
                return view('admin.type_contenus.actions', compact('t'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    

}
