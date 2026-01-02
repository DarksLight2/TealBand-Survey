<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Models\Answer;
use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Enums\SurveyResponseStatus;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Events\EmployeeAnswerSavedEvent;
use Tealband\Survey\Events\EmployeeAnswerSavedWithoutClarifyingEvent;

class AnswerService implements AnswerServiceContract
{
    /**
     * @event EmployeeAnswerSavedEvent
     * @event EmployeeAnswerSavedWithoutClarifyingEvent
     */
    public function employeeAnswer(
        string $answerId,
        string $sessionId
    ): void
    {
        $answer = Answer::query()
            ->find($answerId);

        $session = EmployeeSession::query()->find($sessionId);

        $response = SurveyResponse::query()
            ->updateOrCreate([
                'employee_session_id' => $session->id,
                'question_id' => $answer->question_id,
            ], [
                'answer_id' => $answerId,
                'user_id' => $session->user_id,
                'survey_id' => $session->survey_id,
                'milestone_id' => $session->milestone_id,
                'org_id' => $session->org_id,
                'comment' => '',
                'ai_clarifying' => '',
                'response' => '',
                'summary' => '',
                'status' => SurveyResponseStatus::Active->value,
            ]);

        event(new EmployeeAnswerSavedEvent($response));

        if(is_null($answer->clarifying)) {
            event(new EmployeeAnswerSavedWithoutClarifyingEvent($response));
            Survey::markSurveyResponseAsClosed($response);
        }
    }
}
