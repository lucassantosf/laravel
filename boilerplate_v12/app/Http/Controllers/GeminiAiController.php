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

                    $formatted = collect($functionResponse)->map(function ($item) {
                        return \Carbon\Carbon::parse($item)->translatedFormat('d/m/Y H:i');
                    })->implode(', ');

                    $respostaModelo = ['role' => 'user', 'parts' => [
                        [
                            'functionResponse' => [
                                'name' => $functionCall["name"],
                                'response' => [
                                    'text' => "Horários disponíveis: $formatted"
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

                    // Normalização da data e hora
                    if (isset($args['datetime'])) {
                        $dateTime = null;
                        // Tenta analisar diferentes formatos comuns
                        $formats = ['d/m/Y H:i', 'Y-m-d H:i:s', 'Y-m-d H:i', 'd/m/Y H:00']; // Adicione mais formatos conforme necessário
                        foreach ($formats as $format) {
                            $parsedDateTime = \DateTime::createFromFormat($format, $args['datetime']);
                            if ($parsedDateTime) {
                                $dateTime = $parsedDateTime->format('Y-m-d H:i:s'); // Formato esperado pela sua service
                                break;
                            }
                        }
                        if ($dateTime) {
                            $args['datetime'] = $dateTime;
                        } else {
                            // Se não conseguir formatar, você pode:
                            // 1. Retornar um erro ao Gemini pedindo um formato específico.
                            // 2. Tentar uma abordagem mais robusta de análise de linguagem natural (mais complexo).
                            $functionResponse = ['message' => 'Formato de data e hora inválido. Por favor, informe no formato DD/MM/AAAA HH:MM.'];
                            // ... envie esta resposta para o Gemini para ele corrigir com o usuário ...
                            break; // Interrompe o processamento para pedir correção
                        }
                    } else {
                        // Se 'datetime' estiver faltando, peça ao Gemini
                        $functionResponse = ['message' => 'Por favor, informe a data e hora para agendar.'];
                        // ... envie esta resposta para o Gemini ...
                        break;
                    }

                    // Sanitização básica (exemplo)
                    $args['name'] = trim(strip_tags($args['name'] ?? ''));
                    $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? ''); // Remove caracteres não numéricos, pontos e traços

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

                    $functionResponse = $this->service->search($functionCall["args"]["search"]);

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

                    // Normalização da data e hora (similar ao makeAppointment)
                    if (isset($args['datetime'])) {
                        $dateTime = null;
                        $formats = ['d/m/Y H:i', 'Y-m-d H:i:s', 'Y-m-d H:i', 'd/m/Y H:00'];
                        foreach ($formats as $format) {
                            $parsedDateTime = \DateTime::createFromFormat($format, $args['datetime']);
                            if ($parsedDateTime) {
                                $dateTime = $parsedDateTime->format('Y-m-d H:i:s');
                                break;
                            }
                        }
                        if ($dateTime) {
                            $args['datetime'] = $dateTime;
                        } else {
                            $functionResponse = ['message' => 'Formato de data e hora inválido. Por favor, informe no formato DD/MM/AAAA HH:MM.'];
                            $respostaModeloParaUsuario = ['role' => 'user', 'parts' => [['functionResponse' => ['name' => $functionCall["name"], 'response' => ['text' => json_encode($functionResponse)]]]]];
                            $historico[] = $respostaModeloParaUsuario;
                            $responseGemini = $geminiClient->generateContent($historico);
                            if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
                                $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
                                $historico[] = $respostaModelo;
                            }
                            break;
                        }
                    } else {
                        $functionResponse = ['message' => 'Por favor, informe a nova data e hora para o agendamento (formato DD/MM/AAAA HH:MM).'];
                        $respostaModeloParaUsuario = ['role' => 'user', 'parts' => [['functionResponse' => ['name' => $functionCall["name"], 'response' => ['text' => json_encode($functionResponse)]]]]];
                        $historico[] = $respostaModeloParaUsuario;
                        $responseGemini = $geminiClient->generateContent($historico);
                        if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
                            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
                            $historico[] = $respostaModelo;
                        }
                        break;
                    }

                    // Sanitização básica
                    $args['name'] = trim(strip_tags($args['name'] ?? ''));
                    $args['document'] = preg_replace('/[^0-9.-]/', '', $args['document'] ?? '');

                    $functionResponse = $this->service->update($args); // Chama o serviço sem o ID diretamente

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
