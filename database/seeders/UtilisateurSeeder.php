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

        // Créer un Admin
        Utilisateur::firstOrCreate(
            ['email' => 'admin@culture.bj'],
            [
                'nom' => 'Administrateur',
                'prenom' => 'Principal',
                'password' => Hash::make('password'),
                'telephone' => '01010101',
                'id_role' => $roleAdmin,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Manager
        Utilisateur::firstOrCreate(
            ['email' => 'manager@culture.bj'],
            [
                'nom' => 'Manager',
                'prenom' => 'Site',
                'password' => Hash::make('password'),
                'telephone' => '02020202',
                'id_role' => $roleManager,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Modérateur
        Utilisateur::firstOrCreate(
            ['email' => 'modo@culture.bj'],
            [
                'nom' => 'Modérateur',
                'prenom' => 'Contenu',
                'password' => Hash::make('password'),
                'telephone' => '03030303',
                'id_role' => $roleModo,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Auteur
        Utilisateur::firstOrCreate(
            ['email' => 'auteur@culture.bj'],
            [
                'nom' => 'Auteur',
                'prenom' => 'Renommé',
                'password' => Hash::make('password'),
                'telephone' => '04040404',
                'id_role' => $roleAuteur,
                'email_verified_at' => now(),
            ]
        );

        // Créer un Lecteur
        Utilisateur::firstOrCreate(
            ['email' => 'lecteur@culture.bj'],
            [
                'nom' => 'Lecteur',
                'prenom' => 'Passionné',
                'password' => Hash::make('password'),
                'telephone' => '05050505',
                'id_role' => $roleLecteur,
                'email_verified_at' => now(),
            ]
        );
        
        // Créer un Lecteur pour les tests de paiement (celui qui a peut-être déjà payé)
        Utilisateur::firstOrCreate(
            ['email' => 'testeur@culture.bj'],
            [
                'nom' => 'Testeur',
                'prenom' => 'Paiement',
                'password' => Hash::make('password'),
                'telephone' => '97000000',
                'id_role' => $roleLecteur,
                'email_verified_at' => now(),
            ]
        );

        // Créer l'administrateur demandé
        Utilisateur::firstOrCreate(
            ['email' => 'maurice.comlan@uac.bj'],
            [
                'nom' => 'Comlan',
                'prenom' => 'Maurice',
                'password' => Hash::make('Eneam123'),
                'telephone' => '00000000', // Numéro fictif pour satisfaire les contraintes si nécessaire
                'id_role' => $roleAdmin,
                'email_verified_at' => now(),
            ]
        );
    }
}
