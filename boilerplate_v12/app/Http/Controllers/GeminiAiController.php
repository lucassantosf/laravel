<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients\GeminiAiClient;
use Illuminate\Support\Facades\Cache;

class GeminiAiController extends Controller
{
    public function __construct()
    {

    }

    public function chat(Request $request)
    {
        $geminiClient = new GeminiAiClient();

        $promptUsuario = $request->input('prompt');
        $userId = 1;
        $cacheKey = 'prompt_history_' . $userId;
        $historico = Cache::get($cacheKey, []);

        if ($promptUsuario) {
            $historico[] = ['role' => 'user', 'parts' => [['text' => $promptUsuario]]];
        }

        // $prompt = [
        //     // [
        //     //     'role' => 'user',
        //     //     'parts' => [['text' => $request->input('prompt')]],
        //     // ],
        //     [
        //         'role' => 'user',
        //         'parts' => [['text' => 'Schedule a meeting with Bob and Alice for 03/27/2025 at 10:00 AM about the Q3 planning.']],
        //     ],
        //     [
        //         'role' => 'model',
        //         'parts' => [['text' => 'I am sorry, I cannot schedule meetings. However, I can search for appointments. Would you like me to search for an appointment for Bob or Alice?\n']],
        //     ],
        //     [
        //         'role' => 'user',
        //         'parts' => [['text' => 'No, just list all available times for me please.']],
        //     ],
        // ];

        $responseGemini = $geminiClient->generateContent($historico, $this->getTools());

        $respostaModelo = null;
        if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['text'])) {
            $respostaModelo = ['role' => 'model', 'parts' => [['text' => $responseGemini['candidates'][0]['content']['parts'][0]['text']]]];
        } else if ($responseGemini !== null && isset($responseGemini['candidates'][0]['content']['parts'][0]['functionCall'])) {
            $functionCall = $responseGemini['candidates'][0]['content']['parts'][0]['functionCall'];
            $respostaModelo = ['role' => 'model', 'parts' => [['functionCall' => $functionCall]]];
        }

        if ($respostaModelo) {
            $historico[] = $respostaModelo;
            Cache::put($cacheKey, $historico, 60 * 5); // Exemplo: manter por 5 minutos
        }

        return response()->json(['message' => $return, 'history' => $historico]);
    }
}