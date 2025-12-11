<?php

namespace App\Http\Controllers\Admin;

use App\Models\Parler;
use App\Models\Region;
use App\Models\Langue;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ParlerController extends Controller
{
    public function index()
    {
        $parlers = Parler::with(['region', 'langue'])->get();
        return view('admin.parlers.index', compact('parlers'));
    }

    public function create()
    {
        return view('admin.parlers.create', [
            'regions' => Region::all(),
            'langues' => Langue::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_region' => 'required|exists:regions,id_region',
            'id_langue' => 'required|exists:langues,id_langue',
        ]);

        // Empêcher les doublons
        if (Parler::where('id_region', $request->id_region)
            ->where('id_langue', $request->id_langue)
            ->exists()) 
        {
            return back()->with('error', 'Cette relation existe déjà.');
        }

        Parler::create($request->only('id_region', 'id_langue'));

        return redirect()->route('admin.parlers.index')->with('success', 'Association ajoutée.');
    }

    public function edit($id_region, $id_langue)
    {
        $parler = Parler::where('id_region', $id_region)
                        ->where('id_langue', $id_langue)
                        ->firstOrFail();

        return view('admin.parlers.edit', [
            'parler' => $parler,
            'regions' => Region::all(),
            'langues' => Langue::all(),
        ]);
    }


    public function update(Request $request, $id_region, $id_langue)
    {
        $parler = Parler::where('id_region', $id_region)
                        ->where('id_langue', $id_langue)
                        ->firstOrFail();

        $request->validate([
            'id_region' => 'required|exists:regions,id_region',
            'id_langue' => 'required|exists:langues,id_langue',
        ]);

        // éviter les doublons
        if (
            ($id_region != $request->id_region || $id_langue != $request->id_langue)
            && Parler::where('id_region', $request->id_region)
                    ->where('id_langue', $request->id_langue)
                    ->exists()
        ) {
            return back()->with('error', 'Cette combinaison existe déjà.');
        }

        // mise à jour
        Parler::where('id_region', $id_region)
            ->where('id_langue', $id_langue)
            ->update([
                'id_region' => $request->id_region,
                'id_langue' => $request->id_langue,
            ]);


        return redirect()->route('admin.parlers.index')->with('success', 'Association mise à jour.');
    }


    public function destroy($id_region, $id_langue)
{
    $parler = Parler::where('id_region', $id_region)
                    ->where('id_langue', $id_langue)
                    ->firstOrFail();

    $parler->delete();

    return redirect()->route('admin.parlers.index')->with('success', 'Association supprimée.');
}



    public function data()
    {
        $query = Parler::with(['region', 'langue']);

        return DataTables::of($query)
            ->addColumn('region', function ($p) {
                return $p->region->nom_region ?? '-';
            })
            ->addColumn('langue', function ($p) {
                return $p->langue->nom_langue ?? '-';
            })
            ->addColumn('actions', function ($p) {
                return view('admin.parlers.actions', compact('p'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
