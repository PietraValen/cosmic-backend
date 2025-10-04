<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('project_statistics')->insert([
            [
                'stat_key' => 'total_detections',
                'stat_value' => json_encode(['value' => 90, 'label' => 'Detecções de Ondas Gravitacionais', 'suffix' => '+'])
            ],
            [
                'stat_key' => 'total_volunteers',
                'stat_value' => json_encode(['value' => 5000, 'label' => 'Voluntários Engajados', 'suffix' => '+'])
            ],
            [
                'stat_key' => 'total_glitches_classified',
                'stat_value' => json_encode(['value' => 10000000, 'label' => 'Glitches Classificados', 'suffix' => ''])
            ],
            [
                'stat_key' => 'system_accuracy',
                'stat_value' => json_encode(['value' => 94, 'label' => 'Precisão do Sistema', 'suffix' => '%'])
            ]
        ]);
    }
}
