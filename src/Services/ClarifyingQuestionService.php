<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Events\SavedClarifyResponseEvent;
use Tealband\Survey\Events\GeneratedClarifyQuestionEvent;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;
use Tealband\Survey\Events\EmployeeAnswerSavedWithoutClarifyingEvent;

class ClarifyingQuestionService implements ClarifyingQuestionServiceContract
{
    /**
     * @event GeneratedClarifyQuestionEvent
     */
    public function generate(string $employeeSessionId): string
    {
        $aiClarifying = '';
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
            ->with(['answer:clarifying,id,prompt,weight'])
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $text = "Main question: $question->text; Clarifying question: {$surveyResponse->answer->clarifying}; Clarifying answer: $surveyResponse->comment; Weight: {$surveyResponse->answer->weight}";
        $prompts = [
            ['role' => 'system', 'content' => $surveyResponse->answer->prompt],
            ['role' => 'user', 'content' => $text],
        ];

        $response = AiService::clarifyingQuestion()->handle(array_merge(config('tealband-survey.clarifying-question.prompts'), $prompts));

        if(json_validate($response)) {
            $data = json_decode($response, true);
            if($data['need_clarifying']) {
                $aiClarifying = $data['content'];
            } else {
                event(new EmployeeAnswerSavedWithoutClarifyingEvent($surveyResponse));
                return '';
            }
        }

        $surveyResponse->ai_clarifying = $aiClarifying;
        $surveyResponse->save();

        event(new GeneratedClarifyQuestionEvent($surveyResponse));

        return $aiClarifying;
    }

    public function comment(string $employeeSessionId, string $comment): void
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
        Survey::markSurveyResponseAsClosed($surveyResponse, false);
        $surveyResponse->save();

        event(new SavedClarifyResponseEvent($surveyResponse));
    }
}
