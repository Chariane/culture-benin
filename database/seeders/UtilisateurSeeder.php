<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les ID des rôles
        $roleAdmin = Role::where('nom', 'Administrateur')->first()->id ?? 1;
        $roleManager = Role::where('nom', 'Manager')->first()->id ?? 2;
        $roleModo = Role::where('nom', 'Modérateur')->first()->id ?? 3;
        $roleAuteur = Role::where('nom', 'Auteur')->first()->id ?? 4;
        $roleLecteur = Role::where('nom', 'Lecteur')->first()->id ?? 5;

        // Récupérer une langue par défaut (Français)
        $langueFr = \App\Models\Langue::where('code_langue', 'FR')->first();
        $idLangue = $langueFr ? $langueFr->id_langue : 1;

        // Créer un Admin
        Utilisateur::firstOrCreate(
            ['email' => 'admin@culture.bj'],
            [
                'nom' => 'Administrateur',
                'prenom' => 'Principal',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleAdmin,
                'id_langue' => $idLangue,
            ]
        );

        // Créer un Manager
        Utilisateur::firstOrCreate(
            ['email' => 'manager@culture.bj'],
            [
                'nom' => 'Manager',
                'prenom' => 'Site',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleManager,
                'id_langue' => $idLangue,
            ]
        );

        // Créer un Modérateur
        Utilisateur::firstOrCreate(
            ['email' => 'modo@culture.bj'],
            [
                'nom' => 'Modérateur',
                'prenom' => 'Contenu',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleModo,
                'id_langue' => $idLangue,
            ]
        );

        // Créer un Auteur
        Utilisateur::firstOrCreate(
            ['email' => 'auteur@culture.bj'],
            [
                'nom' => 'Auteur',
                'prenom' => 'Renommé',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleAuteur,
                'id_langue' => $idLangue,
            ]
        );

        // Créer un Lecteur
        Utilisateur::firstOrCreate(
            ['email' => 'lecteur@culture.bj'],
            [
                'nom' => 'Lecteur',
                'prenom' => 'Passionné',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleLecteur,
                'id_langue' => $idLangue,
            ]
        );
        
        // Créer un Lecteur pour les tests de paiement
        Utilisateur::firstOrCreate(
            ['email' => 'testeur@culture.bj'],
            [
                'nom' => 'Testeur',
                'prenom' => 'Paiement',
                'mot_de_passe' => Hash::make('password'),
                'id_role' => $roleLecteur,
                'id_langue' => $idLangue,
            ]
        );

        // Créer l'administrateur demandé
        Utilisateur::firstOrCreate(
            ['email' => 'maurice.comlan@uac.bj'],
            [
                'nom' => 'Comlan',
                'prenom' => 'Maurice',
                'mot_de_passe' => Hash::make('Eneam123'),
                'id_role' => $roleAdmin,
                'id_langue' => $idLangue,
            ]
        );
    }
}
