<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GwoscService;
use App\Models\Detector;
use Illuminate\Support\Facades\Log;

class SyncGwoscRuns extends Command
{
    protected $signature = 'gwosc:sync-runs {--pagesize=50}';
    protected $description = 'Sincroniza detectores e runs GWOSC com a base local.';

    protected GwoscService $gwosc;

    public function __construct(GwoscService $gwosc)
    {
        parent::__construct();
        $this->gwosc = $gwosc;
    }

    public function handle(): int
    {
        Log::info('[GWOSC] Iniciando sincronização de detectores (runs)...');

        $page = 1;
        $pageSize = (int) $this->option('pagesize');
        $totalUpserted = 0;

        // mapeamento simples para nomes (evita match)
        $nameMap = [
            'H1' => 'LIGO Hanford',
            'L1' => 'LIGO Livingston',
            'V1' => 'Virgo',
            'K1' => 'KAGRA',
        ];

        try {
            do {
                $response = $this->gwosc->getRuns(['page' => $page, 'pagesize' => $pageSize]);

                if (!isset($response['success']) || !$response['success']) {
                    Log::error('[GWOSC] Erro ao buscar runs.', [
                        'status' => $response['status'] ?? null,
                        'message' => $response['message'] ?? null,
                    ]);
                    $this->error('Erro ao buscar runs. Veja logs.');
                    return Command::FAILURE;
                }

                $data = $response['data'] ?? null;
                if (!is_array($data)) {
                    Log::warning('[GWOSC] Estrutura inesperada em getRuns', ['data' => $data]);
                    break;
                }

                $runs = $data['results'] ?? [];
                if (empty($runs)) break;

                foreach ($runs as $run) {
                    $runName = $run['name'] ?? null;
                    if (!$runName) continue;

                    // busca detalhes do run
                    $runDetail = $this->gwosc->getRun($runName);

                    if (!isset($runDetail['success']) || !$runDetail['success']) {
                        Log::warning("[GWOSC] Falha ao obter detalhe do run {$runName}", [
                            'status' => $runDetail['status'] ?? null
                        ]);
                        continue;
                    }

                    $detectors = $runDetail['data']['detectors'] ?? [];
                    if (!is_array($detectors) || empty($detectors)) {
                        Log::info("[GWOSC] Nenhum detector listado para run {$runName}");
                        continue;
                    }

                    foreach ($detectors as $code) {
                        if (empty($code)) continue;

                        Log::info("[GWOSC] Run {$runName}");
                        Log::info("[GWOSC] Code {$code}");
                        // updateOrCreate baseado no código (presumo campo unique)
                        $det = Detector::updateOrCreate(
                            ['code' => $code, 'name' => $runName],
                            [
                                'name' => $runName,
                                'code' => $code,
                                'status' => 'active',
                                'description' => "Detectado no run {$runName}",
                                'updated_at' => now(),
                            ]
                        );

                        if ($det) $totalUpserted++;
                    }
                }

                // ver se tem próxima página (API GWOSC devolve 'next' com URL ou null)
                $hasNext = !empty($data['next']);
                $page++;
            } while ($hasNext);

            Log::info('[GWOSC] Detectores sincronizados com sucesso.', ['total' => $totalUpserted]);
            $this->info("Detectores sincronizados: {$totalUpserted}");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('[GWOSC] Exceção durante sync-runs', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->error('Exceção: ' . $e->getMessage() . '. Verifique logs.');
            return Command::FAILURE;
        }
    }
}
