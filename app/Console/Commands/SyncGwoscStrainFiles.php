<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GwoscService;
use App\Models\Glitch;
use App\Models\Detector;
use Illuminate\Support\Facades\Log;

class SyncGwoscStrainFiles extends Command
{
    protected $signature = 'gwosc:sync-strain';
    protected $description = 'Sincroniza arquivos de strain (dados brutos) e gera registros de glitches.';

    protected GwoscService $gwosc;

    public function __construct(GwoscService $gwosc)
    {
        parent::__construct();
        $this->gwosc = $gwosc;
    }

    public function handle(): int
    {
        Log::info('[GWOSC] Iniciando sincronização de arquivos strain...');
        $response = $this->gwosc->getStrainFiles(['pagesize' => 50]);

        if (!$response['success']) {
            Log::error('[GWOSC] Erro ao buscar strain files.');
            return Command::FAILURE;
        }

        $count = 0;
        foreach ($response['data']['results'] as $item) {
            $detector = Detector::where('code', $item['detector'] ?? '')->first();
            if (!$detector) continue;

            Glitch::updateOrCreate(
                [
                    'detector_id' => $detector->id,
                    'timestamp' => gmdate('Y-m-d H:i:s', (int) $item['GPSstart']),
                ],
                [
                    'classification_method' => 'ai',
                    'spectrogram_url' => $item['url'] ?? null,
                    'notes' => 'Arquivo strain importado automaticamente.',
                    'validated' => false,
                ]
            );
            $count++;
        }

        Log::info("[GWOSC] Arquivos strain sincronizados: {$count}");
        $this->info("Arquivos strain sincronizados: {$count}");
        return Command::SUCCESS;
    }
}
