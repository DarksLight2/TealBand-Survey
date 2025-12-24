<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Answer;
use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Data\Answer\AnswerDTO;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Data\Answer\CreateAnswerDTO;
use Tealband\Survey\Data\Answer\UpdateAnswerDTO;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Data\Comment\CreateClarifyingQuestionDTO;
use Tealband\Survey\Contracts\AnswerServiceContract;

/**
 * @template-extends CRUD<CreateAnswerDTO, AnswerDTO, UpdateAnswerDTO>
 */
class AnswerService implements AnswerServiceContract
{
    use CRUD;

    protected string $model = Answer::class;
    protected string $baseDTO = AnswerDTO::class;

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
