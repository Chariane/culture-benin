<?php

namespace App\Http\Controllers\Auteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeContenu;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
class TypeContenuController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {

        $types = TypeContenu::select('id_type_contenu', 'nom_contenu');

        return DataTables::of($types)
            ->addColumn('actions', function ($row) {
                return view('auteur.type_contenus.actions', compact('row'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    return view('auteur.type_contenus.index');
}


    public function store(Request $request)
{
    $request->validate(['nom_contenu' => 'required']);

    $type = new TypeContenu();
    $type->nom_contenu = $request->nom_contenu;

    if (Schema::hasColumn('type_contenus', 'creator_id')) {
        $type->creator_id = Auth::id();
    }

    $type->save();

    return back()->with('success', 'Type ajouté');
}


    public function show($id)
    {
        $type = TypeContenu::findOrFail($id);
        return view('auteur.type_contenus.show', compact('type'));
    }


    public function edit($id)
    {
        $type = TypeContenu::findOrFail($id);
        return view('auteur.type_contenus.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nom_contenu' => 'required']);

        $type = TypeContenu::findOrFail($id);

        $type->nom_contenu = $request->nom_contenu;
        $type->save();

        return redirect()->route('auteur.type_contenus.index')->with('success', 'Modifié');
    }

    public function destroy($id)
    {
        $type = TypeContenu::findOrFail($id);
        $type->delete();
        return back()->with('success', 'Supprimé');
    }
}
