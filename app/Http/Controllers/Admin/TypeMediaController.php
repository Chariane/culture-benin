<?php

namespace App\Http\Controllers\Admin;

use App\Models\TypeMedia;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TypeMediaController extends Controller
{
    public function index()
    {
        return view('admin.type_medias.index');
    }

    public function data()
    {
        return DataTables::of(TypeMedia::select('*'))
            ->addColumn('actions', function ($t) {
                return view('admin.type_medias.actions', compact('t'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.type_medias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_media' => 'required|string|max:255'
        ]);

        TypeMedia::create($validated);
        return redirect()->route('admin.type_medias.index')->with('success', 'Type Media créé.');
    }

    public function show(TypeMedia $typeMedia)
    {
        return view('admin.type_medias.show', compact('typeMedia'));
    }

    public function edit(TypeMedia $typeMedia)
    {
        return view('admin.type_medias.edit', compact('typeMedia'));
    }

    public function update(Request $request, TypeMedia $typeMedia)
    {
        $validated = $request->validate([
            'nom_media' => 'required|string|max:255'
        ]);

        $typeMedia->update($validated);

        return redirect()->route('admin.type_medias.index')->with('success', 'Type Media mis à jour.');
    }

    public function destroy(TypeMedia $typeMedia)
    {
        try {
            $typeMedia->delete();

            return redirect()
                ->route('admin.type_medias.index')
                ->with('success', 'Type de media supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            // Code PostgreSQL : violation FK
            if ($e->getCode() == "23503") {
                return redirect()
                    ->route('admin.type_medias.index')
                    ->with('error', 'Impossible de supprimer ce type de media car des medias y sont rattachés.');
            }

            return redirect()
                ->route('admin.type_medias.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }
}
