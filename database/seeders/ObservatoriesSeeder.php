<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObservatoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('observatories')->insert([
            [
                'name' => 'Very Large Array (VLA)',
                'observatory_type' => 'radio',
                'latitude' => 34.0784,
                'longitude' => -107.6184,
                'location' => 'Socorro, New Mexico',
                'country' => 'USA',
                'description' => 'Conjunto de radiotelescópios para observações de alta resolução'
            ],
            [
                'name' => 'Gemini North',
                'observatory_type' => 'optical',
                'latitude' => 19.8238,
                'longitude' => -155.4689,
                'location' => 'Mauna Kea, Hawaii',
                'country' => 'USA',
                'description' => 'Telescópio óptico/infravermelho de 8.1m'
            ],
            [
                'name' => 'Gemini South',
                'observatory_type' => 'optical',
                'latitude' => -30.2407,
                'longitude' => -70.7366,
                'location' => 'Cerro Pachón',
                'country' => 'Chile',
                'description' => 'Telescópio óptico/infravermelho gêmeo no hemisfério sul'
            ]
        ]);
    }
}
