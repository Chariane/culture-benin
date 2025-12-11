<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeContenu; // Ajoutez cette ligne
use Illuminate\Support\Facades\View;

class LayoutController extends Controller
{
    public function __construct()
    {
        // Partage les types de contenus à toutes les vues
        $this->shareTypesToAllViews();
    }
    
    private function shareTypesToAllViews()
    {
        // Récupérer tous les types de contenus
        $typesContenus = TypeContenu::orderBy('nom_contenu')->get();
        
        // Partager avec toutes les vues
        View::share('typesContenus', $typesContenus);
    }
}