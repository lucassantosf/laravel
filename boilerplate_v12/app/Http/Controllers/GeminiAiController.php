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
            return response()->json('Cache forgot successfully', 200);  
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

                        $functionResponse = $this->service->store($args); 

                        break;
                    case 'findAppointment':

                        $args = $functionCall["args"];
                        
                        $search = $args['document'] ?? $args['name'] ?? null;

                        $functionResponse = $this->service->search($search); 

                        break;
                    case 'updateAppointment':

                        $args = $functionCall["args"];

                        $functionResponse = $this->service->update($args,$args['id']); 

                        break;
                    case 'cancelAppointment':

                        $args = $functionCall["args"]; 

                        $functionResponse = $this->service->cancel($args['id']);   

                        break;
                    default:
                        $functionResponse = ['error'=>"function call not implemented"];
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
}