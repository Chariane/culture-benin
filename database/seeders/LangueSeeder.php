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
        Langue::firstOrCreate(['code_langue'=>'FR'], ['nom_langue'=>'Français']);
        Langue::firstOrCreate(['code_langue'=>'EN'], ['nom_langue'=>'English']);
        Langue::firstOrCreate(['code_langue'=>'GN'], ['nom_langue'=>'Goun']);
        Langue::firstOrCreate(['code_langue'=>'FN'], ['nom_langue'=>'Fon']);
        Langue::firstOrCreate(['code_langue'=>'YB'], ['nom_langue'=>'Yoruba']);
        Langue::firstOrCreate(['code_langue'=>'DN'], ['nom_langue'=>'Dendi']);
        Langue::firstOrCreate(['code_langue'=>'MN'], ['nom_langue'=>'Mina']);
        Langue::firstOrCreate(['code_langue'=>'TR'], ['nom_langue'=>'Tori']);
        Langue::firstOrCreate(['code_langue'=>'NT'], ['nom_langue'=>'Natimba']);
    }
}
