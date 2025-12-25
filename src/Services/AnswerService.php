<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Models\Answer;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Contracts\AnswerServiceContract;

class AnswerService implements AnswerServiceContract
{
    public function employeeAnswer(
        string $answerId,
        string $sessionId
    ): void
    {
        $answer = Answer::query()
            ->find($answerId);

        $session = EmployeeSession::query()->find($sessionId);

        SurveyResponse::query()
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
            ]);
    }
}
