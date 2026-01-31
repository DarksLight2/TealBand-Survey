<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

readonly class ClaudeHandler implements AiHandlerContract
{
    public function __construct(
        public array $opts,
    ) {}

    public function handle(string|array $prompt): string
    {
        $apiKey   = $this->opts['token'];
        $url      = $this->opts['url'];
        $messages = is_array($prompt) ? $prompt : [['role' => 'user', 'content' => $prompt]];

        [$user, $system] = $this->explodeMessageRoles($messages);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
                ->timeout($this->opts['timeout'])
                ->connectTimeout($this->opts['connect_timeout'])
                ->post($url, [
                    'model'       => $this->opts['model'],
                    'max_tokens' => $this->opts['max_tokens'],
                    'system' => $system,
                    'messages'    => $user,
                    'temperature' => $this->opts['temperature'],
                ]);
        } catch (ConnectionException $e) {
            Log::warning('AI handler connection error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return '';
        }

        $body = $response->json();

        if(! $response->successful() || empty($body['content'])) {
            Log::warning('AI handler returned an unsuccessful response.', [
                'response' => $response->json(),
            ]);

            return '';
        }

        $text = '';
        foreach ($body['content'] as $block) {
            if (($block['type'] ?? null) === 'text') {
                $text .= $block['text'];
            }
        }

        return $text;
    }

    private function explodeMessageRoles(array $messages): array
    {
        $systemParts = [];
        $userMessages = [];

        foreach ($messages as $message) {
            if($message['role'] === 'user') {
                $userMessages[] = $message;
            } else {
                $systemParts[] = $message['content'];
            }
        }

        return [$userMessages, implode("\n\n", $systemParts)];
    }
}
