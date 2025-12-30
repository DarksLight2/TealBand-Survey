<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

readonly class ChatGPTHandler implements AiHandlerContract
{
    public function __construct(
        public array $opts,
    ) {}

    public function handle(string|array $prompt): string
    {
        $apiKey = $this->opts['token'];
        $url = $this->opts['url'];
        $messages = is_array($prompt) ? $prompt : [['role' => 'user', 'content' => $prompt]];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type'  => 'application/json',
            ])
                ->timeout($this->opts['timeout'])
                ->post($url, [
                    'model'       => $this->opts['model'],
                    'max_completion_tokens' => $this->opts['max_tokens'],
                    'messages'    => $messages,
                    'temperature' => $this->opts['temperature'],
                ]);
        } catch (ConnectionException $e) {
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

        return $response->json()['choices'][0]['message']['content'];
    }
}
