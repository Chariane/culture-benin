<?php

namespace Database\Seeders;

use App\Models\TypeContenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeContenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Article', 'Vidéo', 'Audio', 'Livre', 'Conte', 'Légende'];

        foreach ($types as $type) {
            TypeContenu::firstOrCreate(['nom_contenu' => $type]);
        }
    }
}
