<?php

namespace Database\Seeders;

use App\Models\TypeMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Image', 'Vidéo', 'Audio', 'Document'];

        foreach ($types as $type) {
            TypeMedia::firstOrCreate(['nom_media' => $type]);
        }
    }
}
