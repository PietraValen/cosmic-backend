<?php

namespace App\Http\Controllers\Gwosc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GwoscService;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    protected GwoscService $gwoscService;

    public function __construct(GwoscService $gwoscService)
    {
        $this->gwoscService = $gwoscService;
    }

    /**
     * Busca todos os catálogos disponíveis na API GWOSC
     * e retorna o array de dados.
     */
    public function fetchCatalogs(): array
    {
        $response = $this->gwoscService->getCatalogs();

        if ($response['success']) {
            Log::info('GWOSC - Catálogos capturados.', [
                'Total' => count($response['data']['results'] ?? []),
            ]);

            
            // Aqui você pode salvar no banco se quiser
            // Catalog::upsert(...)

        } else {
            Log::error('Erro ao buscar catálogos GWOSC', [
                'status' => $response['status'],
                'message' => $response['message'],
            ]);
        }

        return $response;
    }
}
