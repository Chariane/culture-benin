<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre strict à respecter pour éviter les erreurs de clés étrangères
        $this->call([
            RoleSeeder::class,          // 1. Rôles
            LangueSeeder::class,        // 2. Langues
            RegionSeeder::class,        // 3. Régions
            TypeContenuSeeder::class,   // 4. Types de contenus
            TypeMediaSeeder::class,     // 5. Types de médias
            UtilisateurSeeder::class,   // 6. Utilisateurs (dépend des Rôles)
            ContenuSeeder::class,       // 7. Contenus (dépend de tout ce qui précède)
            MediaSeeder::class,         // 8. Médias (dépend de Contenu et TypeMedia)
        ]);
    }
}
