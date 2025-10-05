<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GravitationalWaveEvent;
use App\Models\Detector;
use App\Models\Glitch;
use App\Models\ProjectStatistic;
use Illuminate\Support\Facades\Log;

class UpdateProjectStatistics extends Command
{
    protected $signature = 'gwosc:update-stats';
    protected $description = 'Atualiza estatísticas agregadas do projeto.';

    public function handle(): int
    {
        Log::info('[GWOSC] Atualizando estatísticas agregadas...');

        $stats = [
            'total_events' => GravitationalWaveEvent::count(),
            'total_detectors' => Detector::count(),
            'total_glitches' => Glitch::count(),
            'last_event' => GravitationalWaveEvent::latest('event_date')->first()->name,
            'updated_at' => now()->toIso8601String(),
        ];

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'summary'],
            ['stat_value' => json_encode($stats), 'updated_at' => now()]
        );

        Log::info('[GWOSC] Estatísticas atualizadas com sucesso.', $stats);
        $this->info('Estatísticas atualizadas com sucesso.');
        return Command::SUCCESS;
    }
}
