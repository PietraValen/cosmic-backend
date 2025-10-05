<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DeepseekService
{
    protected string $baseUrl;
    protected string $key;
    protected string $timeout;

    public function __construct()
    {
        $this->baseUrl = env('DEEPSEEK_URL');
        $this->key = env('DEEPSEEK_KEY');
        $this->timeout = env('DEEPSEEK_TIMEOUT', 30);
    }
    
    /**
     * Método genérico para requisições POST
     */
    private function postRequest(string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $defaultHeaders = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->key,
            'Accept: application/json'
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            Log::error('DeepSeek CURL Error', [
                'error' => $error,
                'endpoint' => $endpoint
            ]);

            return [
                'success' => false,
                'status' => 0,
                'message' => 'Erro CURL: ' . $error,
                'data' => null,
            ];
        }

        curl_close($ch);

        $responseData = $response ? json_decode($response, true) : null;

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'status' => $httpCode,
            'message' => $httpCode >= 200 && $httpCode < 300 ? 'OK' : 'Erro na API',
            'data' => $responseData,
        ];
    }

    /**
     * Método genérico para requisições GET
     */
    private function getRequest(string $endpoint, array $queryParams = [], array $headers = []): array
    {
        $url = $this->baseUrl . $endpoint;

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $defaultHeaders = [
            'Authorization: Bearer ' . $this->key,
            'Accept: application/json'
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            Log::error('DeepSeek CURL Error', [
                'error' => $error,
                'endpoint' => $endpoint
            ]);

            return [
                'success' => false,
                'status' => 0,
                'message' => 'Erro CURL: ' . $error,
                'data' => null,
            ];
        }

        curl_close($ch);

        $responseData = $response ? json_decode($response, true) : null;

        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'status' => $httpCode,
            'message' => $httpCode >= 200 && $httpCode < 300 ? 'OK' : 'Erro na API',
            'data' => $responseData,
        ];
    }

    /**
     * Envia uma mensagem para o DeepSeek Chat
     */
    public function sendMessage(string $message, array $parameters = []): array
    {
        $data = [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
            'stream' => false
        ];

        // Merge com parâmetros customizados
        $data = array_merge($data, $parameters);

        $result = $this->postRequest('/chat/completions', $data);

        if ($result['success'] && isset($result['data']['choices'][0]['message']['content'])) {
            $result['content'] = $result['data']['choices'][0]['message']['content'];
            $result['usage'] = $result['data']['usage'] ?? [];
        }

        return $result;
    }

    /**
     * Envia uma conversa com múltiplas mensagens
     */
    public function sendConversation(array $messages, array $parameters = []): array
    {
        $data = [
            'model' => 'deepseek-chat',
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 2000,
            'stream' => false
        ];

        // Merge com parâmetros customizados
        $data = array_merge($data, $parameters);

        $result = $this->postRequest('/chat/completions', $data);

        if ($result['success'] && isset($result['data']['choices'][0]['message']['content'])) {
            $result['content'] = $result['data']['choices'][0]['message']['content'];
            $result['usage'] = $result['data']['usage'] ?? [];
        }

        return $result;
    }

    /**
     * Obtém embeddings de um texto
     */
    public function getEmbeddings(string $text, string $model = 'embedding'): array
    {
        $data = [
            'model' => $model,
            'input' => $text
        ];

        $result = $this->postRequest('/embeddings', $data);

        if ($result['success'] && isset($result['data']['data'][0]['embedding'])) {
            $result['embeddings'] = $result['data']['data'][0]['embedding'];
            $result['usage'] = $result['data']['usage'] ?? [];
        }

        return $result;
    }

    /**
     * Lista os modelos disponíveis
     */
    public function getModels(): array
    {
        return $this->getRequest('/models');
    }

    /**
     * Obtém informações de um modelo específico
     */
    public function getModel(string $modelId): array
    {
        return $this->getRequest("/models/{$modelId}");
    }

    /**
     * Verifica a saúde da API
     */
    public function healthCheck(): array
    {
        $result = $this->getRequest('/models');

        return [
            'success' => $result['success'],
            'status' => $result['status'],
            'message' => $result['success'] ? 'API está funcionando' : 'API com problemas',
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Envia mensagem com streaming (para respostas em tempo real)
     */
    public function sendMessageStream(string $message, callable $callback, array $parameters = []): void
    {
        $data = [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
            'stream' => true
        ];

        $data = array_merge($data, $parameters);

        $url = $this->baseUrl . '/chat/completions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($callback) {
            $callback($data);
            return strlen($data);
        });

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->key,
            'Accept: text/event-stream'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('DeepSeek Stream CURL Error', [
                'error' => curl_error($ch)
            ]);
        }

        curl_close($ch);
    }
}
