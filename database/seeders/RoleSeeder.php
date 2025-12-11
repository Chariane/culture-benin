<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['nom'=>'Administrateur']);
        Role::firstOrCreate(['nom'=>'Manager']);
        Role::firstOrCreate(['nom'=>'Modérateur']);
        Role::firstOrCreate(['nom'=>'Auteur']);
        Role::firstOrCreate(['nom'=>'Lecteur']);
    }
}
