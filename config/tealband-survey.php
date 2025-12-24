<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
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
            'temperature' => 0.7,
            'timeout' => 60,
            'max_tokens' => 4096,
            'token' => env('CHATGPT_TOKEN'),
            'handler' => ChatGPTHandler::class
        ]
    ]
];
