<?php 

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GeminiAiClient
{
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private string $modelName = 'gemini-2.0-flash'; // Ou 'gemini-pro-vision'
    private string $apiKey;
    private Client $httpClient;

    public function __construct(Client $httpClient = null)
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->httpClient = $httpClient ?? new Client();
    }

    public function generateContent(array $prompt, ?array $tools = null): ?array
    {
        $url = $this->baseUrl . $this->modelName . ':generateContent?key=' . $this->apiKey;

        $payload = ['contents' => $prompt];
        if ($tools !== null) {
            $payload['tools'] = $this->getTools();
        }

        try {
            $response = $this->httpClient->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            if ($statusCode >= 200 && $statusCode < 300) {
                return json_decode($body, true);
            } else {
                error_log("Erro na chamada da API do Gemini (Status Code: $statusCode): " . $body);
                return null;
            }

        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    private function getTools(): array
    {
        return [
            'functionDeclarations' => [
                [
                    'name' => 'listAvailability',
                    'description' => 'Return all available time for next 3 days'
                ],
                [
                    'name' => 'findAppointment',
                    'description' => 'Search for appointments made by a client. Accepts name or document',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                            ],
                            'document' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['name'],
                    ],
                ],
            ],
        ];
    }
}