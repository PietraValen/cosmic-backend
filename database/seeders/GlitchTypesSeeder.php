<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlitchTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('glitch_types')->insert([
            [
                'name' => 'Blip',
                'code' => 'BLIP',
                'description' => 'Transientes curtos e de banda larga, geralmente causados por acoplamentos mecânicos',
                'frequency_range' => '10-1000 Hz',
                'duration_range' => '< 0.1s',
                'common_causes' => 'Acoplamento mecânico, movimentos sísmicos',
                'visual_pattern' => 'Explosão curta em múltiplas frequências',
                'color' => '#eab308',
                'icon_name' => 'zap',
                'severity' => 'medium',
            ],
            [
                'name' => 'Whistle',
                'code' => 'WHIS',
                'description' => 'Sinais de frequência crescente, possivelmente de origem eletrônica ou ambiental',
                'frequency_range' => '100-500 Hz',
                'duration_range' => '0.1-2s',
                'common_causes' => 'Interferência eletrônica, ruído ambiental',
                'visual_pattern' => 'Linha ascendente suave no espectrograma',
                'color' => '#06b6d4',
                'icon_name' => 'wind',
                'severity' => 'low',
            ],
            [
                'name' => 'Scattered Light',
                'code' => 'SCAT',
                'description' => 'Padrões em arco causados por dispersão de luz nos espelhos do interferômetro',
                'frequency_range' => '10-100 Hz',
                'duration_range' => '0.5-5s',
                'common_causes' => 'Movimentos sísmicos, dispersão óptica',
                'visual_pattern' => 'Arcos curvos no espectrograma',
                'color' => '#8b5cf6',
                'icon_name' => 'waves',
                'severity' => 'high',
            ]
        ]);
    }
}
