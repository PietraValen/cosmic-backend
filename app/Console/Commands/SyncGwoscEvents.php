<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GwoscService;
use App\Models\GravitationalWaveEvent;
use Illuminate\Support\Facades\Log;

class SyncGwoscEvents extends Command
{
    protected $signature = 'gwosc:sync-events';
    protected $description = 'Sincroniza eventos gravitacionais GWOSC com a base local.';

    protected GwoscService $gwosc;

    public function __construct(GwoscService $gwosc)
    {
        parent::__construct();
        $this->gwosc = $gwosc;
    }

    public function handle(): int
    {
        Log::info('[GWOSC] Iniciando sincronização de eventos...');
        $page = 1;
        $total = 0;

        do {
            $response = $this->gwosc->getEvents(['page' => $page, 'pagesize' => 50]);

            if (!$response['success']) {
                Log::error('[GWOSC] Falha ao buscar página de eventos.', ['page' => $page]);
                break;
            }

            $events = $response['data']['results'] ?? [];
            if (empty($events)) break;

            foreach ($events as $event) {
                $name = $event['name'] ?? null;
                if (!$name) continue;

                $detail = $this->gwosc->getEvent($name);
                $version = $detail['data']['versions'][0] ?? null;
                $detectors = $version['detectors'] ?? [];

                GravitationalWaveEvent::updateOrCreate(
                    ['name' => $name],
                    [
                        'event_date' => isset($version['gps']) ? gmdate('Y-m-d H:i:s', (int) $version['gps']) : now(),
                        'event_type' => $version['catalog'] ?? 'Unknown',
                        'description' => 'Evento sincronizado via GWOSC',
                        'detectors' => $detectors,
                        'updated_at' => now(),
                    ]
                );

                $total++;
            }

            $page++;
        } while (!empty($response['data']['next']));

        Log::info("[GWOSC] Eventos sincronizados: {$total}");
        $this->info("Eventos sincronizados: {$total}");
        return Command::SUCCESS;
    }
}
