<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

readonly class YandexHandler implements AiHandlerContract
{
    public function __construct(public array $opts) {}

    public function handle(string|array $prompt): string
    {
        $apiKey   = $this->opts['token'];
        $modelUri = $this->opts['model'];
        $url      = $this->opts['url'];

        $messages = $this->normalizeMessages($prompt);

        $payload = [
            'modelUri' => $modelUri,
            'completionOptions' => [
                'temperature' => (float) ($this->opts['temperature'] ?? 0.7),
                'maxTokens' => (string) ($this->opts['max_tokens'] ?? '1000'),
            ],
            'messages' => $messages,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Api-Key $apiKey",
                'Content-Type'  => 'application/json',
            ])
                ->timeout((int) ($this->opts['timeout'] ?? 30))
                ->post($url, $payload);

        } catch (ConnectionException $e) {
            Log::warning('YandexHandler connection error: '.$e->getMessage(), ['exception' => $e]);
            return '';
        }

        $body = $response->json();

        if (! $response->successful()) {
            Log::warning('YandexHandler unsuccessful response.', [
                'status' => $response->status(),
                'body'   => $body,
            ]);
            return '';
        }

        $text = $body['result']['alternatives'][0]['message']['text'] ?? null;

        return is_string($text) ? $text : '';
    }

    /**
     * @return array<int, array{role:string, text:string}>
     */
    private function normalizeMessages(string|array $prompt): array
    {
        if (is_string($prompt)) {
            return [['role' => 'user', 'text' => $prompt]];
        }

        $out = [];
        foreach ($prompt as $m) {
            $role = (string) ($m['role'] ?? 'user');
            $text = $m['text'] ?? $m['content'] ?? '';
            $out[] = ['role' => $role, 'text' => (string) $text];
        }

        return $out;
    }
}
