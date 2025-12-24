<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Illuminate\Support\Facades\DB;
use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Events\SavedClarifyResponseEvent;
use Tealband\Survey\Events\GeneratedClarifyQuestionEvent;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;

class ClarifyingQuestionService implements ClarifyingQuestionServiceContract
{
    /**
     * @event GeneratedClarifyQuestionEvent
     */
    public function generate(string $employeeSessionId): string
    {
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
            ->with(['answer:clarifying,id,prompt'])
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $text = "Q1: {$surveyResponse->answer->clarifying}; A1: $surveyResponse->comment";
        $aiClarifying = AiService::make()->handle("{$surveyResponse->answer->prompt} $text");

        $surveyResponse->ai_clarifying = $aiClarifying;
        $surveyResponse->save();

        event(new GeneratedClarifyQuestionEvent($surveyResponse));

        return $aiClarifying;
    }

    public function comment(string $employeeSessionId, string $comment): void
    {
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
//            ->with(['answer:clarifying,id,prompt'])
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $surveyResponse->comment = $comment;
        $surveyResponse->save();
    }

    /**
     * @event SavedClarifyResponseEvent
     */
    public function userAnswer(string $employeeSessionId, string $answer): void
    {
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $surveyResponse->response = $answer;
        $surveyResponse->save();

        event(new SavedClarifyResponseEvent($surveyResponse));
    }
}
