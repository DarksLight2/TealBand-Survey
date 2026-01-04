<?php

declare(strict_types=1);

namespace Tealband\Survey\Services\AI;

use Tealband\Survey\Services\AI\Contracts\AiHandlerContract;

class AiService
{
    private static function make(string|array $provider): AiHandlerContract
    {
        if(is_array($provider)) {
            return app($provider['handler'], $provider);
        } elseif ($provider === 'default') {
            $defaultProvider = config('tealband-survey.ai.provider');
            $currentProvider = config("tealband-survey.ai.providers.$defaultProvider");
            return app($currentProvider['handler'], ['opts' => $currentProvider]);
        } else {
            $currentProvider = config("tealband-survey.ai.providers.$provider");
            return app($currentProvider['handler'], ['opts' => $currentProvider]);
        }
    }

    public static function clarifyingQuestion(): AiHandlerContract
    {
        return self::make(config('tealband-survey.clarifying-question.provider'));
    }

    public static function employeeSessionSummarizer(): AiHandlerContract
    {
        return self::make(config('tealband-survey.summarizers.employee-answer.provider'));
    }

    public static function commonEmployeesSummarizer(): AiHandlerContract
    {
        return self::make(config('tealband-survey.summarizers.common-employees.provider'));
    }

    public static function questionsTypeSummarizer(): AiHandlerContract
    {
        return self::make(config('tealband-survey.summarizers.questions-type.provider'));
    }

    public static function questionSummarizer(): AiHandlerContract
    {
        return self::make(config('tealband-survey.summarizers.question.provider'));
    }

    public static function detailedQuestionSummarizer(): AiHandlerContract
    {
        return self::make(config('tealband-survey.summarizers.detailed-question.provider'));
    }
}
