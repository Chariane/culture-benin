<?php

namespace App\Http\Controllers\Admin;

use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Langue;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = Utilisateur::with(['role','langue'])->get();
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        $roles = Role::all();
        $langues = Langue::all();
        return view('admin.utilisateurs.create', compact('roles','langues'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:utilisateurs,email',
        'mot_de_passe' => 'required|string|min:6',
        'sexe' => 'required|in:Homme,Femme',
        'date_naissance' => 'required|date',
        'statut' => 'required|in:actif,inactif',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'id_role' => 'required|exists:roles,id',
        'id_langue' => 'required|exists:langues,id_langue'
    ]);

    // 📌 Gestion de la photo
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('photos', 'public');
    }

    // 📌 Hash du mot de passe
    $validated['mot_de_passe'] = bcrypt($validated['mot_de_passe']);

    // 📌 Date d'inscription auto
    $validated['date_inscription'] = now();

    // 📌 Création
    Utilisateur::create($validated);

    return redirect()
        ->route('admin.utilisateurs.index')
        ->with('success', 'Utilisateur créé avec succès.');
}



    public function show(Utilisateur $utilisateur)
    {
        return view('admin.utilisateurs.show', compact('utilisateur'));
    }

    public function edit(Utilisateur $utilisateur)
    {
        $roles = Role::all();
        $langues = Langue::all();
        return view('admin.utilisateurs.edit', compact('utilisateur','roles','langues'));
    }

    public function update(Request $request, Utilisateur $utilisateur)
{
    $validated = $request->validate([
        'nom'=>'required|string|max:255',
        'prenom'=>'required|string|max:255',
        'email'=>'required|email|unique:utilisateurs,email,'.$utilisateur->id_utilisateur.',id_utilisateur',
        'mot_de_passe'=>'nullable|string|min:6',
        'sexe'=>'nullable|in:Homme,Femme',
        'date_naissance'=>'nullable|date',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'statut'=>'nullable|in:actif,inactif',
        'id_role'=>'required|exists:roles,id',
        'id_langue'=>'required|exists:langues,id_langue'
    ]);

    // Mot de passe
    if (!empty($validated['mot_de_passe'])) {
        $validated['mot_de_passe'] = bcrypt($validated['mot_de_passe']);
    } else {
        unset($validated['mot_de_passe']);
    }

    // 📌 Nouvelle photo
    if ($request->hasFile('photo')) {

        // Supprimer l'ancienne photo si elle existe
        if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
            Storage::disk('public')->delete($utilisateur->photo);
        }

        // Upload nouvelle photo
        $validated['photo'] = $request->file('photo')->store('photos', 'public');
    }

    $utilisateur->update($validated);

    return redirect()->route('admin.utilisateurs.index')
                     ->with('success','Utilisateur mis à jour.');
}


    public function destroy(Utilisateur $utilisateur)
    {
        try {
            $utilisateur->delete();

            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('success', 'Utilisateur supprimé avec succès.');
        }
        catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == "23503") { 
                return redirect()
                    ->route('admin.utilisateurs.index')
                    ->with('error', 'Impossible de supprimer cet utilisateur car il possède des données liées (commentaires, contenus, médias...).');
            }

            return redirect()
                ->route('admin.utilisateurs.index')
                ->with('error', 'Erreur inconnue lors de la suppression.');
        }
    }




    public function data()
    {
        $query = Utilisateur::with(['role', 'langue']);

        return DataTables::of($query)
            ->addColumn('nom_complet', function ($u) {
                return $u->nom . ' ' . $u->prenom;
            })
            ->addColumn('role', function ($u) {
                return $u->role->nom ?? '';
            })
            ->addColumn('langue', function ($u) {
                return $u->langue->nom_langue ?? '';
            })
            ->addColumn('actions', function ($u) {
                return '
                    <a href="'.route('admin.utilisateurs.show', $u->id_utilisateur).'"
                        class="btn btn-outline-info btn-sm action-icon" title="Voir">
                        <i class="bi bi-eye"></i>
                    </a>

                    <a href="'.route('admin.utilisateurs.edit', $u->id_utilisateur).'"
                        class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="'.route('admin.utilisateurs.destroy', $u->id_utilisateur).'"
                        method="POST" style="display:inline-block">
                        '.csrf_field().method_field('DELETE').'
                        <button onclick="return confirm(\'Supprimer ?\')" 
                                class="btn btn-outline-danger btn-sm action-icon">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

public function deletePhoto($id)
{
    $utilisateur = Utilisateur::findOrFail($id);

    if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
        Storage::disk('public')->delete($utilisateur->photo);
    }

    $utilisateur->photo = null;
    $utilisateur->save();

    return back()->with('success', 'Photo supprimée avec succès.');
}



}
