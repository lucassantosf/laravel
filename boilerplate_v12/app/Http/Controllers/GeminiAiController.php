<?php

namespace App\Http\Controllers;

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

        $responseGemini = $geminiClient->generateContent($historico);
        $respostaModelo = null;
        if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
            $historico[] = $respostaModelo;
        } else if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['functionCall'])) {
            $functionCall = $responseGemini['candidates'][0]['content']['parts'][0]['functionCall'];
            
            $functionCallPart = ['functionCall' => ['name' => $functionCall['name']]];
            if (!empty($functionCall['args'])) {
                $functionCallPart['functionCall']['args'] = $functionCall['args'];
            }

            $respostaModelo = ['role' => 'model', 'parts' => [$functionCallPart]];
            $historico[] = $respostaModelo;

            switch ($functionCall["name"]) {
                case 'listAvailability':
                    $functionResponse = $this->service->index();

                    $respostaModelo = ['role' => 'user', 'parts' => [
                        [
                            'functionResponse' => [
                                'name' => $functionCall["name"],
                                'response' => [
                                    'text' => json_encode($functionResponse)
                                ]
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
                case 'makeAppointment':

                    $args = $functionCall["args"];

                    $args['name'] = trim(strip_tags($args['name'] ?? ''));
                    $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                    $functionResponse = $this->service->store($args);

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
                case 'findAppointment':
                    $args = $functionCall["args"];
                    
                    $search = $args['document'] ?? $args['name'] ?? null;

                    $functionResponse = $this->service->search($search);

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
                case 'updateAppointment':
                    $args = $functionCall["args"];

                    $args['name'] = trim(strip_tags($args['name'] ?? ''));
                    $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                    $functionResponse = $this->service->update($args); 

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
                case 'cancelAppointment':

                    $args = $functionCall["args"];

                    $args['name'] = trim(strip_tags($args['name'] ?? ''));
                    $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                    $search = $args['document'] ?? $args['name'] ?? null;
                    
                    $functionResponse = $this->service->search($search);

                    if(!$functionResponse) {
                        $functionResponse = ['message' => 'Agendamento não encontrado com os dados fornecidos.'];
                        $respostaModeloParaUsuario = ['role' => 'user', 'parts' => [['functionResponse' => ['name' => $functionCall["name"], 'response' => ['text' => json_encode($functionResponse)]]]]];
                        $historico[] = $respostaModeloParaUsuario;
                        $responseGemini = $geminiClient->generateContent($historico);
                        if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
                            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
                            $historico[] = $respostaModelo;
                        }
                        break;
                    }

                    $functionResponse = $this->service->cancel($functionResponse->id);  

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
                    dd("not implemented",$functionCall);
                    break;
            }
        }

        if ($respostaModelo) {
            Cache::put($cacheKey, $historico, 60 * 5); // Exemplo: manter por 5 minutos
        }

        return response()->json(['message' => $respostaModelo, 'history' => $historico]);
    }
}
