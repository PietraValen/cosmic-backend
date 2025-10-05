<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GwoscService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('GWOSC_URL');
    }

    /**
     * Método genérico para requisições GET
     * (Mantido como no seu código)
     */
    private function request(string $endpoint, array $queryParams = [], array $headers = []): array
    {
        $url = $this->baseUrl . $endpoint;

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $defaultHeaders = ['accept: application/json'];
        $allHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Log::info('Retorno.', [
        //     $response,
        // ]);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'status' => 0,
                'message' => 'Erro CURL: ' . $error,
                'data' => null,
            ];
        }

        curl_close($ch);

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'status' => $httpCode,
            'message' => $httpCode >= 200 && $httpCode < 300 ? 'OK' : 'Erro na API',
            'data' => $response ? json_decode($response, true) : null,
        ];
    }

    /**
     * Retorna todos os catálogos de eventos.
     * Endpoint: /catalogs
     */
    public function getCatalogs(array $params = []): array
    {
        // Parâmetros de consulta suportados: page, pagesize
        return $this->request('/catalogs', $params);
    }

    /**
     * Retorna um catálogo específico.
     * Endpoint: /catalogs/{name}
     */
    public function getCatalog(string $name): array
    {
        return $this->request("/catalogs/{$name}");
    }

    /**
     * Retorna todos os eventos públicos.
     * Endpoint: /events
     */
    public function getEvents(array $params = []): array
    {
        // Parâmetros de consulta suportados: page, pagesize
        return $this->request('/events', $params);
    }

    /**
     * Retorna um evento específico pelo nome/alias.
     * Endpoint: /events/{name}
     */
    public function getEvent(string $name): array
    {
        return $this->request("/events/{$name}");
    }

    /**
     * Retorna todas as versões de eventos, com opção de filtros.
     * Endpoint: /event-versions
     */
    public function getEventVersions(array $params = []): array
    {
        // Parâmetros de consulta suportados (exemplo): format, include-default-parameters, lastver, min-<param-name>, max-<param-name>, name-contains, page, pagesize, release
        return $this->request('/event-versions', $params);
    }

    /**
     * Retorna as corridas de ciência e observação passadas (e.g., O1, O2).
     * Endpoint: /runs
     */
    public function getRuns(array $params = []): array
    {
        // Parâmetros de consulta suportados: page, pagesize
        return $this->request('/runs', $params);
    }

    /**
     * Retorna detalhes de uma corrida específica.
     * Endpoint: /runs/{name}
     */
    public function getRun(string $name): array
    {
        return $this->request("/runs/{$name}");
    }

    /**
     * Retorna a lista de parâmetros padrão usados para filtragem e colunas de tabela.
     * Endpoint: /default-parameters
     */
    public function getDefaultParameters(): array
    {
        return $this->request('/default-parameters');
    }

    /**
     * Retorna a lista de arquivos de strain.
     * Endpoint: /strain-files
     */
    public function getStrainFiles(array $params = []): array
    {
        // Parâmetros de consulta suportados: start, stop, detector, sample-rate, pagesize
        return $this->request('/strain-files', $params);
    }

    /**
     * Retorna detalhes de um arquivo de strain específico.
     * Endpoint: /strain-files/{detector}-{gps_time}-{sample_rate}kHz
     */
    public function getStrainFileDetail(string $detector, int $gpsTime, int $sampleRate): array
    {
        $endpoint = "/strain-files/{$detector}-{$gpsTime}-{$sampleRate}kHz";
        return $this->request($endpoint);
    }

    /**
     * Retorna todos os eventos de um catálogo específico.
     * Endpoint: /catalogs/{name}/events
     */
    public function getCatalogEvents(string $catalogName, array $params = []): array
    {
        // Suporta os mesmos filtros de /event-versions, além de include-default-parameters
        return $this->request("/catalogs/{$catalogName}/events", $params);
    }

    /**
     * Retorna os segmentos de uma timeline específica em todas as corridas.
     * Endpoint: /timelines/{timeline_name}/segments
     */
    public function getTimelineSegments(string $timelineName, array $params = []): array
    {
        // Parâmetros de consulta suportados: start, stop, compact, pagesize
        return $this->request("/timelines/{$timelineName}/segments", $params);
    }
}
