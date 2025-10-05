<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DetectorsSeeder::class,
            EventTypeSeeder::class,
            GravitationalWaveEventsSeeder::class,
            ObservatoriesSeeder::class,
            GlitchTypesSeeder::class,
            GlitchesSeeder::class,
            UserStatsSeeder::class,
            ScientificDiscoveriesSeeder::class,
            ProjectStatisticsSeeder::class,
            UserClassifications::class
        ]);
    }
}
