<?php

namespace App\Http\Controllers\Admin;

use App\Models\Langue;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class LangueController extends Controller
{
    public function index()
    {
        $langues = Langue::all();
        return view('admin.langues.index', compact('langues'));
    }

    public function create()
    {
        return view('admin.langues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_langue'=>'required|string|max:255',
            'code_langue'=>'required|string|max:10|unique:langues,code_langue',
            'description'=>'nullable|string'
        ]);

        Langue::create($validated);
        return redirect()->route('admin.langues.index')->with('success','Langue créée.');
    }

    public function show(Langue $langue)
    {
        return view('admin.langues.show', compact('langue'));
    }

    public function edit(Langue $langue)
    {
        return view('admin.langues.edit', compact('langue'));
    }

    public function update(Request $request, Langue $langue)
    {
        $validated = $request->validate([
            'nom_langue'=>'required|string|max:255',
            'code_langue'=>'required|string|max:10|unique:langues,code_langue,'.$langue->id_langue.',id_langue',
            'description'=>'nullable|string'
        ]);

        $langue->update($validated);
        return redirect()->route('admin.langues.index')->with('success','Langue mise à jour.');
    }

    public function destroy(Langue $langue)
    {
        try {
            $langue->delete();

            return redirect()
                ->route('admin.langues.index')
                ->with('success', 'Langue supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == "23503") { 
                return redirect()
                    ->route('admin.langues.index')
                    ->with('error', 'Impossible de supprimer cette langue car il possède des données liées (utilisateurs,,,)');
            }

            return redirect()
                ->route('admin.langues.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }

    public function data()
    {
        return DataTables::of(Langue::query())
            ->addColumn('actions', function ($l) {
                return view('admin.langues.actions', compact('l'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
