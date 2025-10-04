<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlitchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $h1Id = DB::table('detectors')->where('code', 'H1')->value('id');
        $l1Id = DB::table('detectors')->where('code', 'L1')->value('id');
        $v1Id = DB::table('detectors')->where('code', 'V1')->value('id');

        $blipId = DB::table('glitch_types')->where('code', 'BLIP')->value('id');
        $whisId = DB::table('glitch_types')->where('code', 'WHIS')->value('id');
        $scatId = DB::table('glitch_types')->where('code', 'SCAT')->value('id');

        DB::table('glitches')->insert([
            [
                'detector_id' => $h1Id,
                'glitch_type_id' => $blipId,
                'timestamp' => '2024-01-15 14:30:22',
                'peak_frequency' => 250.5,
                'snr' => 12.3,
                'duration' => 0.05,
                'confidence' => 0.92,
                'classification_method' => 'hybrid',
                'validated' => true,
            ],
            [
                'detector_id' => $l1Id,
                'glitch_type_id' => $whisId,
                'timestamp' => '2024-01-15 15:45:10',
                'peak_frequency' => 180.2,
                'snr' => 8.7,
                'duration' => 1.2,
                'confidence' => 0.87,
                'classification_method' => 'ai',
                'validated' => false,
            ],
            [
                'detector_id' => $v1Id,
                'glitch_type_id' => $scatId,
                'timestamp' => '2024-01-16 02:12:33',
                'peak_frequency' => 75.8,
                'snr' => 15.6,
                'duration' => 2.8,
                'confidence' => 0.95,
                'classification_method' => 'hybrid',
                'validated' => true,
            ]
        ]);
    }
}
