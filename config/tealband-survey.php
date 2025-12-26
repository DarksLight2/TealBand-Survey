<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Organization;
use Tealband\Survey\Services\AI\ChatGPTHandler;

return [
    'models' => [
        'user' => User::class,
        'org' => Organization::class,
    ],
    'ai' => [
        'provider' => [
            'model' => 'gpt-5',
            'temperature' => 1,
            'timeout' => 60,
            'max_tokens' => 4096,
            'token' => env('CHATGPT_TOKEN'),
            'handler' => ChatGPTHandler::class
        ]
    ],
    'summarizers' => [
        'employee-session' => [
            'prompt' => 'Ты получаешь список ответов пользователя по которым необходимо сделать сводку и обобщение о том, какие вопросы его больше всего волнуют + какие проблемы нужно решить. Ограничение в 300 символов. В ответе пиши текст который должен быть конечным.',
        ]
    ],
];
