<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_types')->insert([
            [
                'name' => 'confident',
                'description' => 'Eventos com alta confiabilidade, confirmados por múltiplos métodos de análise.',
            ],
            [
                'name' => 'marginal',
                'description' => 'Eventos com baixa confiabilidade, candidatos que podem ter origem instrumental.',
            ],
            [
                'name' => 'auxiliary',
                'description' => 'Eventos descartados ou auxiliares, não incluídos como detecções formais.',
            ],
            [
                'name' => 'preliminary',
                'description' => 'Eventos preliminares, sujeitos a revisão ou confirmação futura.',
            ]
        ]);
    }
}
