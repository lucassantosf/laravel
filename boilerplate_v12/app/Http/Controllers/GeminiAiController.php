<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use Illuminate\Http\Request;
use App\Clients\GeminiAiClient;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\AppointmentServiceInterface;

class GeminiAiController extends Controller
{
    protected $appointmentRepository;

    public function __construct(AppointmentServiceInterface $service)
    {
        parent::__construct($service);
    }

    public function chat(Request $request)
    {
        $geminiClient = new GeminiAiClient();

        $promptUsuario = $request->input('prompt');
        $userId = 1;
        $cacheKey = 'prompt_history_' . $userId;
        // Cache::forget($cacheKey);
        // dd('d');
        $historico = Cache::get($cacheKey, []);

        if ($promptUsuario) {
            $historico[] = ['role' => 'user', 'parts' => [['text' => $promptUsuario]]];
        }

        $responseGemini = $geminiClient->generateContent($historico);

        $respostaModelo = null;
        if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
            $historico[] = $respostaModelo;
        } else if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['functionCall'])) {
            $functionCall = $responseGemini['candidates'][0]['content']['parts'][0]['functionCall'];
            $respostaModelo = ['role' => 'model', 'parts' => [['functionCall' => $functionCall]]];
            $historico[] = $respostaModelo;

            switch ($functionCall["name"]) {
                case 'findAppointment':

                    $functionResponse = $this->service->search($functionCall["args"]["name"]);

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

                    if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
                        $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
                        $historico[] = $respostaModelo; 
                    } 

                    break;

                default:
                    # code...
                    break;
            }
        }

        if ($respostaModelo) {
            Cache::put($cacheKey, $historico, 60 * 5); // Exemplo: manter por 5 minutos
        }

        return response()->json(['message' => $respostaModelo, 'history' => $historico]);
    }
}
