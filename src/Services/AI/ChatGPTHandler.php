<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ConnectException;
use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

class ChatGPTHandler implements AiHandlerContract
{
    public function handle(string $prompt): string
    {
        $aiConfig = config('tealband-survey.ai.provider');
        $apiKey = $aiConfig['token'];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type'  => 'application/json',
            ])
                ->timeout($aiConfig['timeout'])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => $aiConfig['model'],
                    'max_completion_tokens' => $aiConfig['max_tokens'],
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => $aiConfig['temperature'],
                ]);
        } catch (ConnectException $e) {
            Log::warning('AI handler connection error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return '';
        }

        $body = $response->json();

        if(! $response->successful() || empty($body['choices'])) {
            Log::warning('AI handler returned an unsuccessful response.', [
                'response' => $response->json(),
            ]);

            return '';
        }

        return $response->json()['choices'][0];
    }
}
