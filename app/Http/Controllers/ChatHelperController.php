<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatHelperController extends Controller
{
    /**
     * Send a message to OpenAI and get a response
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');

        // Prepare the prompt for OpenAI
        $prompt = "You are PHP developer assistant. Answer only to questions related to PHP and Web development. Answer shortly. If question is not relevant answer with one phrase \"I can't help you\". There is the user message: \n$userMessage";

        try {
            // Get API key from environment
            $apiKey = env('OPENAI_API_KEY');

            if (!$apiKey) {
                return response()->json([
                    'error' => 'OpenAI API key not configured'
                ], 500);
            }

            // Call OpenAI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are PHP developer assistant. Answer only to questions related to PHP and Web development. Answer shortly. If question is not relevant answer with one phrase "I can\'t help you".'
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $aiResponse = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

                return response()->json([
                    'message' => $userMessage,
                    'response' => $aiResponse
                ]);
            } else {
                return response()->json([
                    'error' => 'Failed to get response from OpenAI: ' . ($response->json()['error']['message'] ?? 'Unknown error')
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
