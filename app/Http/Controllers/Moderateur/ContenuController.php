<?php

namespace App\Http\Controllers\Moderateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contenu;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ContenuController extends Controller
{
    /**
     * DataTable: contenus en attente
     */
    public function enAttenteData(Request $request)
    {
        $query = Contenu::where('statut', 'En attente')
            ->with(['auteur', 'type']);

        return DataTables::of($query)
            ->addColumn('auteur', fn($c) => $c->auteur->nom ?? '—')
            ->addColumn('type', fn($c) => $c->type->nom_contenu ?? '—')
            ->addColumn('actions', function($c) {
                $data = [
                    'id_contenu' => $c->id_contenu,
                    'titre' => e($c->titre),
                    'texte' => e($c->texte),
                    'auteur' => $c->auteur->nom ?? '—',
                    'date_creation' => $c->date_creation,
                ];
                $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');

                $btn = '<button class="btn btn-sm btn-primary" onclick="openContenuModal(' . $json . ')">Voir</button>';
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * DataTable: contenus validés par ce modérateur (archive)
     */
    public function validesData()
    {
        $userId = Auth::id();

        $query = Contenu::where('statut', 'Bon')
            ->where('id_moderateur', $userId)
            ->with('auteur');

        return DataTables::of($query)
            ->addColumn('auteur', fn($c) => $c->auteur->nom ?? '—')
            ->editColumn('date_validation', fn($c) => Carbon::parse($c->date_validation)->format('Y-m-d H:i'))
            ->make(true);
    }

    /**
     * DataTable: contenus médiocres par ce modérateur (archive)
     */
    public function mediocresData()
    {
        $userId = Auth::id();

        $query = Contenu::where('statut', 'Médiocre')
            ->where('id_moderateur', $userId)
            ->with('auteur');

        return DataTables::of($query)
            ->addColumn('auteur', fn($c) => $c->auteur->nom ?? '—')
            ->editColumn('date_validation', fn($c) => Carbon::parse($c->date_validation)->format('Y-m-d H:i'))
            ->make(true);
    }

    /**
     * Show single contenu (used by modal via AJAX; you can also use server render)
     */
    public function show(Contenu $contenu)
    {
        // On peut autoriser via middleware IsModerateur; ici juste retourner la vue
        // renvoyer JSON ou view selon usage. Pour simplicité, renvoyons JSON si AJAX.
        if(request()->ajax()) {
            $contenu->load('auteur','type','region','langue');
            return response()->json($contenu);
        }
        return view('moderateur.contenus.show', compact('contenu'));
    }

    /**
     * Change statut (Bon / Médiocre) — action AJAX
     */
    public function changeStatut(Request $request, Contenu $contenu)
    {
        $request->validate([
            'statut' => 'required|in:Bon,Médiocre',
        ]);

        $statut = $request->input('statut');

        $contenu->statut = $statut;
        $contenu->id_moderateur = Auth::id();
        $contenu->date_validation = now();
        $contenu->save();

        // recalculer stats à renvoyer au client
        $en_attente = Contenu::where('statut', 'En attente')->count();
        $valides = Contenu::where('statut', 'Bon')->where('id_moderateur', Auth::id())->count();
        $rejetes = Contenu::where('statut', 'Médiocre')->where('id_moderateur', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => "Contenu mis à jour en '{$statut}'.",
            'stats' => [
                'en_attente' => $en_attente,
                'valides' => $valides,
                'rejetes' => $rejetes,
            ]
        ]);
    }
}
