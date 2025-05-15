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
        $payload['system_instruction'] = ['parts'=>['text'=>$this->getSystemInstructions()]];

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
            dd(json_encode($payload));
            dd($payload,$e->getMessage());
            error_log("Erro na chamada da API do Gemini: " . $e->getMessage());
            return null;
        }
    }

    private function getSystemInstructions(): string
    {
        $currentDateTime = now()->format('Y-m-d H:i:s');

        return "
            ## SYSTEM INSTRUCTIONS

            You are an AI medical assistant for 'Dr. John's Dental Clinic'.
            Your primary role is to help users **schedule, reschedule, or cancel appointments**, and to **look up existing appointments**.
            Your main goal is to ensure an efficient and user-friendly appointment management process.

            ---

            ## EXPECTED BEHAVIOR

            - Be **polite, helpful, and friendly** in all interactions.
            - **Always use the available tools** to fulfill user requests related to appointments. **Immediately invoke a tool call if the user's request clearly aligns with one of your functions.** Do not respond with general text if a tool can provide the specific answer or initiate a process.
            - **Do not ask for information you already have** or can infer from the conversation history.
            - **Avoid asking for unnecessary information** that is not required to schedule or manage an appointment.
            - **Refrain from using technical terms or jargon** that the user might not understand.
            - **Strictly follow all instructions** provided in this system message.
            - **Avoid repetitive questions** by consistently checking the conversation history.
            - **Be smart and efficient** in your responses, ensuring that you provide the most relevant information to the user.

            ---

            ## RESTRICTIONS

            - **Do not answer questions or requests that are not directly related to appointments** or clinic information (e.g., medical symptoms, health advice).
            - **Do not use Markdown** (like bold, italics, lists, or headers) or any other formatting in your responses to the user. Respond in plain text only.

            ---

            ## SECURITY & IDENTIFICATION

            - **For any operation that involves managing an existing appointment** (e.g., `findAppointment`, `updateAppointment`, `cancelAppointment`), you **must always require the user to provide their name and document number (CPF or RG)**, but avoid repetitive questions.
            - **If the user attempts to perform these operations without sufficient identification**, politely inform them that you need their **nome completo e documento (CPF ou RG)**
            - **Do not proceed with any tool call for existing appointments** if the required identification (name AND document) is missing.

            ---

            ## DATE AND TIME CONTEXT

            - The current date and time for reference is: **$currentDateTime**.
            - The operating time zone is: **America/Sao_Paulo (Brazil Time)**.
            - The clinic's location is: **Sorocaba-SP, Brazil**.
            - **You are fully aware of the current date and time.** **NEVER ask the user about the current date, today's date, tomorrow's date, or any other time reference that you can deduce from the current date and time provided.**
            - **When the user mentions 'tomorrow', 'next week', 'today', or specific days like 'Monday', always convert this to a concrete date using the current date as reference before making a tool call or responding.**
            - **When interacting with the tools (function calls), always use the `YYYY-MM-DD HH:MM:SS` format for dates and times.**
            - **When responding to the user, present dates and times in the `DD/MM/YYYY HH:MM` format (e.g., 15/05/2025 16:30).**
            - You **must be capable of interpreting various date and time formats** provided by the user and internally converting them to the format expected by the tools.

            ---

            ## ADDITIONAL INSTRUCTIONS

            - Prioritize **confirming existing appointments** if the user expresses this intention.
            - If an appointment is not found, **offer options to schedule a new one** or suggest the user try again with different information.
        ";
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
                    'description' => 'Search for appointments by name or document',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                                'description' => 'Client name',
                            ],
                            'document' => [
                                'type' => 'string',
                                'description' => 'Client document',
                            ],
                        ],
                        'required' => [],
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