<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GravitationalWaveEvent;
use App\Models\Detector;
use App\Models\Glitch;
use App\Models\Observatory;
use App\Models\ProjectStatistic;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateProjectStatistics extends Command
{
    protected $signature = 'gwosc:update-stats';
    protected $description = 'Atualiza estatísticas agregadas do projeto.';

    public function handle(): int
    {
        Log::info('[GWOSC] Atualizando estatísticas agregadas...');

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'total_events'],
            ['stat_value' => GravitationalWaveEvent::count(), 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'total_detectors'],
            ['stat_value' => Detector::count(), 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'total_glitches'],
            ['stat_value' => Glitch::count(), 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'last_event'],
            ['stat_value' => GravitationalWaveEvent::latest('event_date')->first()->name, 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'updated_at'],
            ['stat_value' => now()->toIso8601String(), 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'total_volunteers'],
            ['stat_value' => User::count(), 'updated_at' => now()]
        );

        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'total_observatories'],
            ['stat_value' => Observatory::count(), 'updated_at' => now()]
        );

        Log::info('[GWOSC] Estatísticas atualizadas com sucesso.');
        $this->info('Estatísticas atualizadas com sucesso.');
        return Command::SUCCESS;
    }
}
