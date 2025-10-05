<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GravitationalWaveEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gravitational_wave_events')->insert([
            [
                'name' => 'GW150914',
                'event_date' => '2015-09-14 09:50:45',
                'latitude' => -70.6,
                'longitude' => -113.9,
                'event_type' => 1,
                'mass_1' => 36.0,
                'mass_2' => 29.0,
                'distance_mpc' => 440,
                'description' => 'Primeira detecção de ondas gravitacionais da história',
                'significance' => 'Prêmio Nobel de Física 2017',
                'detectors' => json_encode(['H1', 'L1'])
            ],
            [
                'name' => 'GW170817',
                'event_date' => '2017-08-17 12:41:04',
                'latitude' => -23.38,
                'longitude' => -69.40,
                'event_type' => 'BNS',
                'mass_1' => 1.46,
                'mass_2' => 1.27,
                'distance_mpc' => 40,
                'description' => 'Fusão de estrelas de nêutrons com contraparte eletromagnética',
                'significance' => 'Primeira detecção com luz (kilonova)',
                'detectors' => json_encode(['H1', 'L1', 'V1'])
            ],
            [
                'name' => 'GW190521',
                'event_date' => '2019-05-21 03:02:29',
                'latitude' => 45.0,
                'longitude' => -75.0,
                'event_type' => 'BBH',
                'mass_1' => 85.0,
                'mass_2' => 66.0,
                'distance_mpc' => 5300,
                'description' => 'Fusão de buracos negros de massa intermediária',
                'significance' => 'Maior massa detectada até então',
                'detectors' => json_encode(['H1', 'L1', 'V1'])
            ]
        ]);
    }
}
