<?php

namespace App\Http\Controllers\Auteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeMedia;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
class TypeMediaController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {

        $types = TypeMedia::select('id_type_media', 'nom_media');

        return DataTables::of($types)
            ->addColumn('actions', function ($row) {
                return view('auteur.type_medias.actions', compact('row'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    return view('auteur.type_medias.index');
}


    public function store(Request $request)
{
    $request->validate(['nom_media' => 'required']);

    $type = new TypeMedia();
    $type->nom_media = $request->nom_media;

    if (Schema::hasColumn('type_medias', 'creator_id')) {
        $type->creator_id = Auth::id();
    }

    $type->save();

    return back()->with('success', 'Type ajouté');
}


    public function show($id)
    {
        $type = TypeMedia::findOrFail($id);
        return view('auteur.type_medias.show', compact('type'));
    }


    public function edit($id)
    {
        $type = TypeMedia::findOrFail($id);
        return view('auteur.type_medias.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nom_media' => 'required']);

        $type = TypeMedia::findOrFail($id);

        $type->nom_media = $request->nom_media;
        $type->save();

        return redirect()->route('auteur.type_medias.index')->with('success', 'Modifié');
    }

    public function destroy($id)
    {
        $type = TypeMedia::findOrFail($id);
        $type->delete();
        return back()->with('success', 'Supprimé');
    }
}
