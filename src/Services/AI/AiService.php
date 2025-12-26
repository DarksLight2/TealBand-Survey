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

    public static function clarifyingQuestion(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }

    public static function employeeSessionSummarizer(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }

    public static function commonEmployeesSummarizer(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }

    public static function questionsTypeSummarizer(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }

    public static function questionSummarizer(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }

    public static function detailedQuestionSummarizer(): AiHandlerContract
    {
        return app(config('tealband-survey.ai.provider.handler'));
    }
}
