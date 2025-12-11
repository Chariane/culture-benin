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
        // Initialisation robuste des clés étrangères
        // Si le type spécifique n'est pas trouvé, on prend le premier disponible pour éviter le crash FK
        $typeArticleObj = \App\Models\TypeContenu::where('nom_contenu', 'Article')->first() 
                         ?? \App\Models\TypeContenu::first();
        $typeArticle = $typeArticleObj ? $typeArticleObj->id_type_contenu : null;

        $typeVideoObj = \App\Models\TypeContenu::where('nom_contenu', 'Vidéo')->first() 
                       ?? \App\Models\TypeContenu::first();
        $typeVideo = $typeVideoObj ? $typeVideoObj->id_type_contenu : null;

        $regionObj = \App\Models\Region::first();
        $region = $regionObj ? $regionObj->id_region : null;

        $langueObj = \App\Models\Langue::first();
        $langue = $langueObj ? $langueObj->id_langue : null;

        $auteurObj = \App\Models\Utilisateur::where('email', 'auteur@culture.bj')->first() 
                    ?? \App\Models\Utilisateur::first();
        $auteur = $auteurObj ? $auteurObj->id_utilisateur : null;

        $moderateurObj = \App\Models\Utilisateur::where('email', 'modo@culture.bj')->first() 
                        ?? \App\Models\Utilisateur::first();
        $moderateur = $moderateurObj ? $moderateurObj->id_utilisateur : null;

        // Si une des tables est vide, impossible de créer du contenu
        if (!$typeArticle || !$region || !$langue || !$auteur) {
            $this->command->error("Impossible de créer des contenus : Données dépendantes manquantes (Type/Region/Langue/User).");
            return;
        }

        Contenu::create([
            'titre' => '100 marmites de 50 kg en 24h : le défi XXL de Eden Food',
            'texte' => 'Le second défi se joue sur un format plus court mais tout aussi spectaculaire. Love Anick Ploka, restauratrice connue sous le nom de "Eden Food" et figure populaire de TikTok, veut préparer 100 marmites de 50 kg en 24 heures. Le défi commence samedi 29 novembre à 7 h. Son objectif : nourrir 30 000 personnes. Elle affirme qu\'il existe une dimension caritative derrière ce projet. Selon elle, le défi lui a été "imposé" par ses fans, qui lui ont demandé de prouver son savoir-faire à grande échelle.',
            'date_creation' => now(),
            'statut' => 'Bon',
            'date_validation' => now(),
            'id_region' => $region,
            'id_langue' => $langue,
            'id_auteur' => $auteur,
            'id_type_contenu' => $typeArticle,
            'id_moderateur' => $moderateur,
            'premium' => true,
            'prix' => 100.00
        ]);

        // Contenu Premium 2
        Contenu::create([
            'titre' => 'Les secrets du Vaudou : Documentaire Exclusif',
            'texte' => 'Plongez au cœur des traditions ancestrales du Bénin avec ce documentaire unique. Découvrez les rituels, les les lieux sacrés et les témoignages inédits des grands prêtres. Un voyage spirituel et culturel qui ne vous laissera pas indifférent. Ce contenu premium vous donne accès à des séquences jamais filmées auparavant.',
            'date_creation' => now()->subDays(2),
            'statut' => 'Bon',
            'date_validation' => now()->subDays(1),
            'id_region' => $region,
            'id_langue' => $langue,
            'id_auteur' => $auteur,
            'id_type_contenu' => $typeVideo,
            'id_moderateur' => $moderateur,
            'premium' => true,
            'prix' => 500.00
        ]);

        // Contenu Gratuit
        Contenu::create([
            'titre' => 'Découverte de la cuisine béninoise : La Sauce Graine',
            'texte' => 'Apprenez à cuisiner la délicieuse sauce graine, un classique de la gastronomie béninoise. Cette recette facile à suivre vous guidera étape par étape pour réussir ce plat savoureux. Idéal pour un repas en famille le dimanche midi. Bon appétit !',
            'date_creation' => now()->subDays(5),
            'statut' => 'Bon',
            'date_validation' => now()->subDays(4),
            'id_region' => $region,
            'id_langue' => $langue,
            'id_auteur' => $auteur,
            'id_type_contenu' => $typeArticle,
            'id_moderateur' => $moderateur,
            'premium' => false,
            'prix' => 0.00
        ]);
    }
}