<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients\GeminiAiClient;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\AppointmentServiceInterface;
use Illuminate\Support\Facades\Log;

class GeminiAiController extends Controller
{
    protected $appointmentRepository;
    protected $geminiClient;

    public function __construct(AppointmentServiceInterface $service, GeminiAiClient $geminiClient)
    {
        parent::__construct($service);
        $this->geminiClient = $geminiClient;
    }

    public function chat(Request $request)
    { 
        $geminiClient = new GeminiAiClient();

        $promp = $request->input('prompt');
        $userId = 1;
        $cacheKey = 'prompt_history_' . $userId;

        if($request->input('clear')) {
            Cache::forget($cacheKey);
        }

        $historico = Cache::get($cacheKey, []);

        if ($promp) {
            $historico[] = ['role' => 'user', 'parts' => [['text' => $promp]]];
        }

        do {
            $responseGemini = $geminiClient->generateContent($historico);
            $respostaModelo = null;
            if ($responseGemini !== null && (isset($responseGemini['candidates'][0]['content']['parts'][0]['functionCall']) 
                || isset($responseGemini['candidates'][0]['content']['parts'][1]['functionCall']))) {

                $functionCall = $responseGemini['candidates'][0]['content']['parts'][0]['functionCall'] ?? $responseGemini['candidates'][0]['content']['parts'][1]['functionCall'];
                
                $functionCallPart = ['functionCall' => ['name' => $functionCall['name']]];
                if (!empty($functionCall['args'])) {
                    $functionCallPart['functionCall']['args'] = $functionCall['args'];
                }

                $respostaModelo = ['role' => 'model', 'parts' => [$functionCallPart]];
                $historico[] = $respostaModelo;
                    
                switch ($functionCall["name"]) {
                    case 'listAvailability':
                        $functionResponse = $this->service->index(); 

                        break;
                    case 'makeAppointment':

                        $args = $functionCall["args"];

                        $args['name'] = trim(strip_tags($args['name'] ?? ''));
                        $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                        $functionResponse = $this->service->store($args); 

                        break;
                    case 'findAppointment':
                        $args = $functionCall["args"];
                        
                        $search = $args['document'] ?? $args['name'] ?? null;

                        $functionResponse = $this->service->search($search); 

                        break;
                    case 'updateAppointment':
                        $args = $functionCall["args"];

                        $args['name'] = trim(strip_tags($args['name'] ?? ''));
                        $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                        $functionResponse = $this->service->update($args); 

                        break;
                    case 'cancelAppointment':

                        $args = $functionCall["args"]; 

                        $functionResponse = $this->service->cancel($args['id']);   

                        break;
                    default:
                        dd("not implemented",$functionCall);
                        break;
                }

                $respostaModelo = ['role' => 'user', 'parts' => [
                    [
                        'functionResponse' => [
                            'name' => $functionCall["name"],
                            'response' => ['text' => json_encode($functionResponse)]
                        ]
                    ]
                ]]; 

                $historico[] = $respostaModelo;
                $responseGemini = $geminiClient->generateContent($historico); 
            }

        } while ( isset($responseGemini['candidates'][0]['content']['parts'][0]['functionCall']) );

        if (isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
            $historico[] = $respostaModelo; 
        }

        if ($respostaModelo) {
            Cache::put($cacheKey, $historico, 60 * 5); // Exemplo: manter por 5 minutos
        }

        return response()->json(['message' => $respostaModelo, 'history' => $historico]);
    }
    
    /**
     * Generate a response from Gemini and handle any function calls
     * 
     * @param array $history Conversation history
     * @return array Response data including message and updated history
     */
    protected function generateResponse(array $history)
    {
        // Get initial response from Gemini
        $responseGemini = $this->geminiClient->generateContent($history);
        $modelResponse = null;
        $updatedHistory = $history;
        
        // Check if response contains text or function call
        if ($this->hasTextResponse($responseGemini)) {
            // If model responds with text but should have used a function,
            // we can force a function call for certain user intents
            $lastUserMessage = $this->getLastUserMessage($history);
            
            if ($this->shouldUseListAvailability($lastUserMessage)) {
                // Force listAvailability function call
                Log::info('Forcing listAvailability function call');
                return $this->handleForcedFunctionCall('listAvailability', [], $updatedHistory);
            } else {
                // Handle regular text response
                $text = $responseGemini['candidates'][0]['content']['parts'][0]['text'];
                $modelResponse = ['role' => 'model', 'parts' => [['text' => $text]]];
                $updatedHistory[] = $modelResponse;
            }
        } elseif ($this->hasFunctionCall($responseGemini)) {
            // Handle function call
            $functionCall = $responseGemini['candidates'][0]['content']['parts'][0]['functionCall'];
            $functionCallPart = [
                'functionCall' => [
                    'name' => $functionCall['name']
                ]
            ];
            
            if (!empty($functionCall['args'])) {
                $functionCallPart['functionCall']['args'] = $functionCall['args'];
            }
            
            // Add function call to history
            $modelResponse = ['role' => 'model', 'parts' => [$functionCallPart]];
            $updatedHistory[] = $modelResponse;
            
            // Process function based on name
            $functionResponse = $this->executeFunctionCall($functionCall);
            
            // Add function response to history
            $functionResponseMessage = [
                'role' => 'user',
                'parts' => [
                    [
                        'functionResponse' => [
                            'name' => $functionCall['name'],
                            'response' => ['text' => json_encode($functionResponse)]
                        ]
                    ]
                ]
            ];
            
            $updatedHistory[] = $functionResponseMessage;
            
            // Get follow-up response from Gemini
            $followUpResponse = $this->geminiClient->generateContent($updatedHistory);
            
            // Process follow-up response
            if ($this->hasTextResponse($followUpResponse)) {
                $text = $followUpResponse['candidates'][0]['content']['parts'][0]['text'];
                $followUpModelResponse = ['role' => 'model', 'parts' => [['text' => $text]]];
                $updatedHistory[] = $followUpModelResponse;
                $modelResponse = $followUpModelResponse;
            }
        } else {
            // Handle error case
            $modelResponse = ['role' => 'model', 'parts' => [['text' => 'Desculpe, não consegui processar sua solicitação. Por favor, tente novamente.']]];
            $updatedHistory[] = $modelResponse;
        }
        
        return [
            'message' => $modelResponse,
            'history' => $updatedHistory
        ];
    }
    
    /**
     * Handle a forced function call when the model doesn't use function calling
     * 
     * @param string $functionName Function name
     * @param array $args Function arguments
     * @param array $history Conversation history
     * @return array Response data
     */
    protected function handleForcedFunctionCall($functionName, $args, $history)
    {
        // Create function call part
        $functionCallPart = [
            'functionCall' => [
                'name' => $functionName
            ]
        ];
        
        if (!empty($args)) {
            $functionCallPart['functionCall']['args'] = $args;
        }
        
        // Add function call to history
        $modelResponse = ['role' => 'model', 'parts' => [$functionCallPart]];
        $history[] = $modelResponse;
        
        // Execute function
        $functionCall = [
            'name' => $functionName,
            'args' => $args
        ];
        
        $functionResponse = $this->executeFunctionCall($functionCall);
        
        // Add function response to history
        $functionResponseMessage = [
            'role' => 'user',
            'parts' => [
                [
                    'functionResponse' => [
                        'name' => $functionName,
                        'response' => ['text' => json_encode($functionResponse)]
                    ]
                ]
            ]
        ];
        
        $history[] = $functionResponseMessage;
        
        // Get follow-up response from Gemini
        $followUpResponse = $this->geminiClient->generateContent($history);
        
        // Process follow-up response
        if ($this->hasTextResponse($followUpResponse)) {
            $text = $followUpResponse['candidates'][0]['content']['parts'][0]['text'];
            $followUpModelResponse = ['role' => 'model', 'parts' => [['text' => $text]]];
            $history[] = $followUpModelResponse;
            $modelResponse = $followUpModelResponse;
        }
        
        return [
            'message' => $modelResponse,
            'history' => $history
        ];
    }
    
    /**
     * Execute a function call and return the result
     * 
     * @param array $functionCall Function call data
     * @return mixed Function result
     */
    protected function executeFunctionCall(array $functionCall)
    {
        $name = $functionCall['name'];
        $args = $functionCall['args'] ?? [];
        
        Log::info('Executing function call', [
            'function' => $name,
            'args' => $args
        ]);
        
        switch ($name) {
            case 'listAvailability':
                return $this->service->index();
                
            case 'makeAppointment':
                // Sanitize inputs
                $args['name'] = $this->sanitizeName($args['name'] ?? '');
                $args['document'] = $this->sanitizeDocument($args['document'] ?? '');
                return $this->service->store($args);
                
            case 'findAppointment':
                $search = $args['document'] ?? $args['name'] ?? null;
                return $this->service->search($search);
                
            case 'updateAppointment':
                // Sanitize inputs
                $args['name'] = $this->sanitizeName($args['name'] ?? '');
                $args['document'] = $this->sanitizeDocument($args['document'] ?? '');
                return $this->service->update($args);
                
            case 'cancelAppointment':
                // Find appointment first
                $search = $args['document'] ?? $args['name'] ?? null;
                $appointment = $this->service->search($search);
                
                if (!$appointment) {
                    return ['message' => 'Agendamento não encontrado com os dados fornecidos.'];
                }
                
                return $this->service->cancel($appointment->id);
                
            default:
                throw new \Exception("Function not implemented: {$name}");
        }
    }
    
    /**
     * Check if Gemini response contains a text response
     * 
     * @param array|null $response Gemini response
     * @return bool True if response contains text
     */
    protected function hasTextResponse($response)
    {
        return $response !== null && 
               isset($response['candidates'][0]['content']['parts'][0]['text']);
    }
    
    /**
     * Check if Gemini response contains a function call
     * 
     * @param array|null $response Gemini response
     * @return bool True if response contains a function call
     */
    protected function hasFunctionCall($response)
    {
        return $response !== null && 
               isset($response['candidates'][0]['content']['parts'][0]['functionCall']);
    }
    
    /**
     * Get the last user message from history
     * 
     * @param array $history Conversation history
     * @return string Last user message or empty string
     */
    protected function getLastUserMessage($history)
    {
        for ($i = count($history) - 1; $i >= 0; $i--) {
            if ($history[$i]['role'] === 'user' && 
                isset($history[$i]['parts'][0]['text'])) {
                return $history[$i]['parts'][0]['text'];
            }
        }
        return '';
    }
    
    /**
     * Check if the user message indicates they want to list availability
     * 
     * @param string $message User message
     * @return bool True if message indicates listing availability
     */
    protected function shouldUseListAvailability($message)
    {
        $message = strtolower($message);
        $availabilityKeywords = [
            'disponibilidade', 'horários', 'horarios', 'disponível', 'disponivel',
            'quando', 'que horas', 'que dia', 'agenda', 'agendar', 'marcar'
        ];
        
        foreach ($availabilityKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize a name input
     * 
     * @param string $name Name to sanitize
     * @return string Sanitized name
     */
    protected function sanitizeName($name)
    {
        return trim(strip_tags($name));
    }
    
    /**
     * Sanitize a document input
     * 
     * @param string $document Document to sanitize
     * @return string Sanitized document
     */
    protected function sanitizeDocument($document)
    {
        return preg_replace('/[^0-9.-]/', '', $document);
    }  
}
