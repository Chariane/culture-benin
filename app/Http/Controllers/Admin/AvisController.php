<?php

namespace App\Http\Controllers\Admin;

use App\Models\Avis;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $avis = Avis::with('utilisateur')->select('*');
            
            return DataTables::of($avis)
                ->addIndexColumn()
                ->addColumn('utilisateur_info', function($row) {
                    if ($row->utilisateur) {
                        return '
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    ' . ($row->utilisateur->photo ? 
                                        '<img src="' . asset('storage/' . $row->utilisateur->photo) . '" 
                                             alt="' . $row->utilisateur->prenom . '" 
                                             class="rounded-circle" 
                                             width="40" 
                                             height="40">' : 
                                        '<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                              style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                         </div>') . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>' . $row->utilisateur->prenom . ' ' . $row->utilisateur->nom . '</strong><br>
                                    <small class="text-muted">' . $row->utilisateur->email . '</small>
                                </div>
                            </div>
                        ';
                    }
                    return '<span class="text-muted">Utilisateur inconnu</span>';
                })
                ->addColumn('message_short', function($row) {
                    return '<div class="text-truncate" style="max-width: 300px;" title="' . e($row->message) . '">
                                ' . e($row->message) . '
                            </div>';
                })
                ->addColumn('date_formatted', function($row) {
                    try {
                        if ($row->date) {
                            if ($row->date instanceof Carbon) {
                                return $row->date->format('d/m/Y H:i');
                            } else {
                                return Carbon::parse($row->date)->format('d/m/Y H:i');
                            }
                        }
                        return '-';
                    } catch (\Exception $e) {
                        return '-';
                    }
                })
                ->addColumn('actions', function($row) {
                    $btn = '
                    <div class="btn-group" role="group">
                        <form action="' . route('admin.avis.destroy', $row->id_avis) . '" 
                              method="POST" 
                              class="d-inline delete-form"
                              data-confirm="Êtes-vous sûr de vouloir supprimer cet avis ?">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>';
                    return $btn;
                })
                ->rawColumns(['utilisateur_info', 'message_short', 'actions'])
                ->make(true);
        }
        
        return view('admin.avis.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $avis = Avis::findOrFail($id);
        $avis->delete();
        
        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis supprimé avec succès.');
    }
}