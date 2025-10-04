<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScientificDiscoveriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventId = DB::table('gravitational_wave_events')->where('name', 'GW150914')->value('id');

        DB::table('scientific_discoveries')->insert([
            [
                'title' => 'Primeira detecção de ondas gravitacionais',
                'description' => 'Confirmação da existência de ondas gravitacionais prevista por Einstein',
                'discovery_date' => '2015-09-14',
                'related_event_id' => $eventId,
                'researchers' => json_encode(['LIGO Collaboration']),
                'publication_url' => 'https://journals.aps.org/prl/abstract/10.1103/PhysRevLett.116.061102',
                'significance' => 'Prêmio Nobel de Física 2017',
            ]
        ]);
    }
}
