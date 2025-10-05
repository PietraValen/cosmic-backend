<?php

namespace App\Console\Commands;

use App\Models\Detector;
use App\Models\EventType;
use App\Models\Glitch;
use App\Models\GlitchType;
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
                $version = $detail['data']['versions'][0] ?? [];
                $detectorsCatalog = $version['detectors'] ?? [];

                $strainData = $this->gwosc->getEventStrain($name);
                $strainList = $strainData['data']['results'] ?? [];

                $utcDates = [];
                $detectorsFromStrain = [];
                $urlFromStrain = [];

                foreach ($strainList as $s) {
                    if (isset($s['utc_start'])) {
                        $utcDates[] = $s['utc_start'];
                    }
                    if (isset($s['detector'])) {
                        $detectorsFromStrain[] = $s['detector'];
                    }
                    if (isset($s['hdf5_url'])) {
                        $urlFromStrain[] = $s['hdf5_url'];
                    }
                }

                $eventUrl = $urlFromStrain[0] ?? null;

                $eventDate = !empty($utcDates)
                    ? min($utcDates)
                    : (isset($version['gps']) ? gmdate('Y-m-d H:i:s', (int) $version['gps']) : now());

                $detectors = array_values(array_unique(array_merge($detectorsCatalog, $detectorsFromStrain)));

                $eventVersions = $this->gwosc->getEventVersions($name, [
                    'format' => 'json',
                    'include-default-parameters' => 'true',
                    'lastver' => 'true',
                ]);

                $paramMap = [];
                foreach ($eventVersions['data']['default_parameters'] as $param) {
                    $paramMap[$param['name']] = $param;
                }

                $mass1 = $paramMap['mass_1_source']['best'] ?? null;
                $mass2 = $paramMap['mass_2_source']['best'] ?? null;
                $distance = $paramMap['luminosity_distance']['best'] ?? null;
                $far = $paramMap['far']['best'] ?? null;
                $ra = isset($paramMap['ra']['best']) ? rad2deg($paramMap['ra']['best']) : null;
                $dec = isset($paramMap['dec']['best']) ? rad2deg($paramMap['dec']['best']) : null;

                $eventTypeId = EventType::where('name', $version['catalog'])->value('id');

                GravitationalWaveEvent::updateOrCreate(
                    ['name' => $name],
                    [
                        'event_date'      => $eventDate,
                        'event_type'      => $eventTypeId,
                        'description'     => 'Evento sincronizado via GWOSC. Fontes strain: ' . count($strainList) . ' arquivos.',
                        'detectors'       => json_encode($detectors),
                        'mass_1'          => $mass1,
                        'mass_2'          => $mass2,
                        'latitude'        => $dec,
                        'longitude'       => $ra,
                        'spectrogram_url' => $eventUrl,
                        'distance_mpc'    => $distance,
                        'false_alarm_rate' => $far,
                        'updated_at' => now(),
                    ]
                );

                $analise = $this->classificarEvento([
                    'false_alarm_rate' => $far,
                    'mass_1' => $mass1,
                    'mass_2' => $mass2,
                    'distance_mpc' => $distance,
                    'significance' => null,
                    'detectors' => $detectors,
                ]);

                if ($analise['classificacao'] === 'glitch provável' && $analise['glitch_code']) {
                    $glitchExiste = Glitch::where('timestamp', $eventDate)
                        ->where('classification_method', 'ai')
                        ->exists();

                    if (!$glitchExiste) {
                        $glitchTypeId = GlitchType::where('code', $analise['glitch_code'])->value('id');
                        $detectorId = Detector::where('code', $detectors[0] ?? null)->value('id');

                        Glitch::create([
                            'detector_id' => $detectorId,
                            'glitch_type_id' => $glitchTypeId,
                            'timestamp' => $eventDate,
                            'confidence' => round(1 - min((float) $far, 1), 4),
                            'classification_method' => 'ai',
                            'spectrogram_url' => $eventUrl,
                            'notes' => "Glitch do tipo {$analise['glitch_code']} inferido a partir do evento {$name}.",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        Log::info("[GWOSC] Glitch {$analise['glitch_code']} criado para evento {$name}");
                    }
                }

                $total++;
            }

            $page++;
        } while (!empty($response['data']['next']));

        Log::info("[GWOSC] Eventos sincronizados: {$total}");
        $this->info("Eventos sincronizados: {$total}");
        return Command::SUCCESS;
    }

    private function classificarEvento(array $evento): array
    {
        $far = floatval($evento['false_alarm_rate'] ?? 0);
        $detectors = $evento['detectors'] ?? [];
        $mass1 = $evento['mass_1'] ?? null;
        $mass2 = $evento['mass_2'] ?? null;
        $distance = $evento['distance_mpc'] ?? null;
        $significance = $evento['significance'] ?? null;

        if ($far > 0.01 && count($detectors) === 1 && (!$mass1 || !$mass2)) {
            return [
                'classificacao' => 'glitch provável',
                'glitch_code' => 'BLIP',
            ];
        }

        if ($far > 0.001 && $distance && $distance > 5000 && count($detectors) >= 2) {
            return [
                'classificacao' => 'glitch provável',
                'glitch_code' => 'WHIS',
            ];
        }

        if (!$mass1 && !$mass2 && !$distance && !$significance && count($detectors) === 1) {
            return [
                'classificacao' => 'glitch provável',
                'glitch_code' => 'SCAT',
            ];
        }

        if ($mass1 && $mass2 && $distance && count($detectors) >= 2 && $far <= 0.01) {
            return [
                'classificacao' => 'evento legítimo',
                'glitch_code' => null,
            ];
        }

        return [
            'classificacao' => 'evento incerto',
            'glitch_code' => null,
        ];
    }
}
