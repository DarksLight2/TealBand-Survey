<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Tealband\Survey\Services\AI\ChatGPTHandler;

return [
    'models' => [
        'user' => User::class,
        'employee' => Employee::class,
        'department' => Department::class,
    ],
    'ai' => [
        'providers' => [
            'chat-gpt' => [
                'model' => 'gpt-5',
                'handler' => ChatGPTHandler::class
            ]
        ]
    ]
];
