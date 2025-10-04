<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detectors')->insert([
            [
                'name' => 'LIGO Hanford Observatory',
                'code' => 'H1',
                'latitude' => 46.4550,
                'longitude' => -119.4080,
                'location' => 'Hanford, Washington',
                'country' => 'USA',
                'arm_length_km' => 4.0,
                'operational_since' => '2002-01-01',
                'description' => 'Detector de ondas gravitacionais com braços de 4 km'
            ],
            [
                'name' => 'LIGO Livingston Observatory',
                'code' => 'L1',
                'latitude' => 30.5630,
                'longitude' => -90.7740,
                'location' => 'Livingston, Louisiana',
                'country' => 'USA',
                'arm_length_km' => 4.0,
                'operational_since' => '2002-01-01',
                'description' => 'Segundo detector LIGO nos EUA'
            ],
            [
                'name' => 'Virgo',
                'code' => 'V1',
                'latitude' => 43.6314,
                'longitude' => 10.5045,
                'location' => 'Cascina, Pisa',
                'country' => 'Italy',
                'arm_length_km' => 3.0,
                'operational_since' => '2007-01-01',
                'description' => 'Detector europeu de ondas gravitacionais'
            ],
            [
                'name' => 'KAGRA',
                'code' => 'K1',
                'latitude' => 36.4125,
                'longitude' => 137.3059,
                'location' => 'Kamioka, Gifu',
                'country' => 'Japan',
                'arm_length_km' => 3.0,
                'operational_since' => '2020-02-25',
                'description' => 'Detector subterrâneo criogênico no Japão'
            ],
            [
                'name' => 'GEO600',
                'code' => 'G1',
                'latitude' => 52.2467,
                'longitude' => 9.8083,
                'location' => 'Ruthe, Hannover',
                'country' => 'Germany',
                'arm_length_km' => 0.6,
                'operational_since' => '2002-01-01',
                'description' => 'Detector alemão de pesquisa e desenvolvimento'
            ]
        ]);
    }
}
