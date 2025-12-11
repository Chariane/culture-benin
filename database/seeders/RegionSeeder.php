<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'nom_region' => 'Atlantique',
                'description' => 'Région côtière comprenant plusieurs communes importantes.',
                'population' => 1990000,
                'superficie' => 3233.0,
                'localisation' => 'Sud'
            ],
            [
                'nom_region' => 'Littoral',
                'description' => 'Région urbaine abritant la ville de Cotonou.',
                'population' => 700000,
                'superficie' => 79.0,
                'localisation' => 'Sud'
            ],
            [
                'nom_region' => 'Borgou',
                'description' => 'Grande région du nord-est du Bénin.',
                'population' => 1200000,
                'superficie' => 25856.0,
                'localisation' => 'Nord-Est'
            ],
            [
                'nom_region' => 'Alibori',
                'description' => 'Région frontalière du Nigeria et du Niger.',
                'population' => 867000,
                'superficie' => 26242.0,
                'localisation' => 'Nord'
            ],
            [
                'nom_region' => 'Mono',
                'description' => 'Région rurale avec une importante activité agricole.',
                'population' => 495000,
                'superficie' => 1605.0,
                'localisation' => 'Sud-Ouest'
            ],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
