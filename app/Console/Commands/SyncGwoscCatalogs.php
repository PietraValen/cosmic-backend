<?php

namespace App\Console\Commands;

use App\Models\EventType;
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
        $page = 1;
        $total = 0;

        do {
            $response = $this->gwosc->getCatalogs(['page' => $page, 'pagesize' => 50]);

            if (!$response['success']) {
                Log::error('[GWOSC] Erro ao buscar catálogos.', ['status' => $response['status']]);
                return Command::FAILURE;
            }

            $events = $response['data']['results'] ?? [];
            if (empty($events)) break;

            foreach ($events as $event) {
                $name = $event['name'] ?? null;
                if (!$name) continue;

                $description = $event['description'] ?? null;

                EventType::updateOrCreate(
                    ['name' => $name],
                    ['description' => $description, 'updated_at' => now()]
                );

                $total++;
            }
            $page++;
        } while (!empty($response['data']['next']));


        Log::info("[GWOSC] Catálogos sincronizados: {$total}");
        $this->info("Catálogos sincronizados: {$total}");
        return Command::SUCCESS;
    }
}
