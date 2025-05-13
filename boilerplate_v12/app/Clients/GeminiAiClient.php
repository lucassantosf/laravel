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

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->httpClient = $httpClient ?? new Client();
    }

    public function generateContent(array $prompt): ?array
    {
        $url = $this->baseUrl . $this->modelName . ':generateContent?key=' . $this->apiKey;

        $payload = [
            'contents' => $prompt,
            'generationConfig' => [
                'temperature' => 0,
            ],
        ];
        $payload['tools'] = $this->getTools();

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
            // dd(json_encode($payload));
            dd($payload,$e->getMessage());
            error_log("Erro na chamada da API do Gemini: " . $e->getMessage());
            return null;
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
                            'search' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['search'],
                    ],
                ],
                [
                    'name' => 'makeAppointment',
                    'description' => 'Creates an appointment in our clinic. Its necessary inform name , document and datetime to schedule the appointment',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                            ],
                            'document' => [
                                'type' => 'string',
                            ],
                            'datetime' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['name','document','datetime'],
                    ],
                ],
                [
                    'name' => 'updateAppointment',
                    'description' => 'Updates an appointment in our clinic that has already been scheduled. Its necessary inform name , document and datetime to schedule the appointment',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                            ],
                            'document' => [
                                'type' => 'string',
                            ],
                            'datetime' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['name','document','datetime'],
                    ],
                ],
                [
                    'name' => 'cancelAppointment',
                    'description' => 'Cancel an appointment in our clinic that has already been scheduled',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                            ],
                            'document' => [
                                'type' => 'string',
                            ],
                            'datetime' => [
                                'type' => 'string',
                            ],
                        ],
                        'required' => ['name','document','datetime'],
                    ],
                ],
            ],
        ];
    }
}