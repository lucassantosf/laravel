<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients\GeminiAiClient;

class GeminiAiController extends Controller
{
    public function __construct()
    {

    }

    public function chat(Request $request)
    {
        $geminiClient = new GeminiAiClient();

        $prompt = [
            [
                'role' => 'user',
                'parts' => [['text' => $request->input('prompt')]],
            ],
        ];

        $response = $geminiClient->generateContent($prompt);

        if ($response !== null && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            $return = "Resposta (Guzzle): " . $response['candidates'][0]['content']['parts'][0]['text'] . "\n";
        } else {
            $return = $response;
        }

        return response()->json(['message' => $return]);
    }
}