<?php

namespace Database\Seeders;

use App\Models\Contenu; // ← IMPORTANT !
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contenu::create([
            'titre' => '100 marmites de 50 kg en 24h : le défi XXL de Eden Food',
            'texte' => 'Le second défi se joue sur un format plus court mais tout aussi spectaculaire. Love Anick Ploka, restauratrice connue sous le nom de "Eden Food" et figure populaire de TikTok, veut préparer 100 marmites de 50 kg en 24 heures. Le défi commence samedi 29 novembre à 7 h. Son objectif : nourrir 30 000 personnes. Elle affirme qu\'il existe une dimension caritative derrière ce projet. Selon elle, le défi lui a été "imposé" par ses fans, qui lui ont demandé de prouver son savoir-faire à grande échelle.',
            'date_creation' => now(),
            'statut' => 'Bon',
            'date_validation' => now(),
            'id_region' => 2,
            'id_langue' => 1,
            'id_auteur' => 10,
            'id_type_contenu' => 10,
            'id_moderateur' => 3,
            'premium' => true,
            'prix' => 100.00
        ]);
    }
}