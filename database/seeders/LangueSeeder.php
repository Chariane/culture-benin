<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Langue;

class LangueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Langue::create(['code_langue'=>'FR', 'nom_langue'=>'Français']);
        Langue::create(['code_langue'=>'EN', 'nom_langue'=>'English']);
        Langue::create(['code_langue'=>'GN', 'nom_langue'=>'Goun']);
        Langue::create(['code_langue'=>'FN', 'nom_langue'=>'Fon']);
        Langue::create(['code_langue'=>'YB', 'nom_langue'=>'Yoruba']);
        Langue::create(['code_langue'=>'DN', 'nom_langue'=>'Dendi']);
        Langue::create(['code_langue'=>'MN', 'nom_langue'=>'Mina']);
        Langue::create(['code_langue'=>'TR', 'nom_langue'=>'Tori']);
        Langue::create(['code_langue'=>'NT', 'nom_langue'=>'Natimba']);
    }
}
