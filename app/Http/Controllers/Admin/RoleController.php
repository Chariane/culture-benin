<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function data()
    {
        return DataTables::of(Role::query())
            ->addColumn('actions', function ($r) {
                return view('admin.roles.actions', compact('r'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create() { return view('admin.roles.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255'
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success','Rôle créé.');
    }

    public function show(Role $role) { return view('admin.roles.show', compact('role')); }

    public function edit(Role $role) { return view('admin.roles.edit', compact('role')); }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255'
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success','Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == "23503") { 
                return redirect()
                    ->route('admin.roles.index')
                    ->with('error', 'Impossible de supprimer ce rôle car il possède des données liées (utilisateurs,,,)');
            }

            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }
}
