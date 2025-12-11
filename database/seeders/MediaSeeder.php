<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Contenu;
use App\Models\TypeMedia;
use App\Models\Langue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les contenus
        $contenus = Contenu::all();

        // On récupère le type Image
        $typeImage = TypeMedia::where('nom_media', 'Image')->first();
        $idTypeImage = $typeImage ? $typeImage->id_type_media : 1;

        // On récupère une langue
        $langue = Langue::first();
        $idLangue = $langue ? $langue->id_langue : 1;

        foreach ($contenus as $contenu) {
            Media::create([
                'id_type_media' => $idTypeImage,
                // 'nom_fichier' n'existe pas dans la base
                'chemin' => 'images/culture-beninoise.jpg', // Renommé 'chemin_fichier' -> 'chemin'
                'id_contenu' => $contenu->id_contenu,
                'id_langue' => $idLangue
            ]);
        }
    }
}
