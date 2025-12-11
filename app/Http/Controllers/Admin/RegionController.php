<?php

namespace App\Http\Controllers\Admin;

use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_region'=>'required|string|max:255',
            'description'=>'nullable|string',
            'population'=>'nullable|integer',
            'superficie'=>'nullable|numeric',
            'localisation'=>'nullable|string'
        ]);

        Region::create($validated);

        return redirect()->route('admin.regions.index')->with('success','Région créée.');
    }

    public function show(Region $region)
    {
        return view('admin.regions.show', compact('region'));
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'nom_region'=>'required|string|max:255',
            'description'=>'nullable|string',
            'population'=>'nullable|integer',
            'superficie'=>'nullable|numeric',
            'localisation'=>'nullable|string'
        ]);

        $region->update($validated);

        return redirect()->route('admin.regions.index')->with('success','Région mise à jour.');
    }

    public function destroy(Region $region)
    {
        try {
            $region->delete();

            return redirect()
                ->route('admin.regions.index')
                ->with('success', 'Region supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == "23503") { 
                return redirect()
                    ->route('admin.regions.index')
                    ->with('error', 'Impossible de supprimer cette région car il possède des données liées (utilisateurs,,,)');
            }

            return redirect()
                ->route('admin.regions.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }

    public function data()
    {
        return DataTables::of(Region::query())
            ->addColumn('actions', function ($r) {
                return view('admin.regions.actions', compact('r'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
