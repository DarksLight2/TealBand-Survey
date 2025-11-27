<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

class AiService
{
    public static function make(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }
}
