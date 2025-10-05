<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GwoscService;
use App\Models\ProjectStatistic;
use Illuminate\Support\Facades\Log;

class SyncGwoscCatalogs extends Command
{
    protected $signature = 'gwosc:sync-catalogs';
    protected $description = 'Sincroniza catálogos GWOSC e atualiza cache em project_statistics.';

    protected GwoscService $gwosc;

    public function __construct(GwoscService $gwosc)
    {
        parent::__construct();
        $this->gwosc = $gwosc;
    }

    public function handle(): int
    {
        Log::info('[GWOSC] Iniciando sincronização de catálogos...');
        $response = $this->gwosc->getCatalogs(['pagesize' => 100]);

        if (!$response['success']) {
            Log::error('[GWOSC] Erro ao buscar catálogos.', ['status' => $response['status']]);
            return Command::FAILURE;
        }

        $data = $response['data']['results'] ?? [];
        ProjectStatistic::updateOrCreate(
            ['stat_key' => 'gwosc_catalogs'],
            ['stat_value' => json_encode($data), 'updated_at' => now()]
        );

        Log::info('[GWOSC] Catálogos sincronizados com sucesso.', ['count' => count($data)]);
        $this->info('Catálogos sincronizados: ' . count($data));
        return Command::SUCCESS;
    }
}
