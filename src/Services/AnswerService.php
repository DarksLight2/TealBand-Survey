<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Answer;
use Tealband\Survey\Data\Answer\AnswerDTO;
use Tealband\Survey\Data\Answer\CreateAnswerDTO;
use Tealband\Survey\Data\Answer\UpdateAnswerDTO;
use Tealband\Survey\Models\EmployeeSessionAnswer;
use Tealband\Survey\Data\Comment\CreateCommentDTO;

/**
 * @template-extends CRUD<CreateAnswerDTO, AnswerDTO, UpdateAnswerDTO>
 */
class AnswerService
{
    use CRUD;

    protected string $model = Answer::class;
    protected string $baseDTO = AnswerDTO::class;

    public function employeeAnswer(
        int $answerId,
        string $sessionId
    ): void
    {
        $answer = Answer::query()
            ->with(['question:text'])
            ->find($answerId);

        EmployeeSessionAnswer::query()
            ->updateOrCreate([
                'question_id' => $answer->question_id,
                'employee_session_id' => $sessionId
            ], ['answer_id' => $answerId]);

        $commentService = new CommentService();

        if($answer->trigger_followup) {
            $commentService->create(new CreateCommentDTO(
                employeeSessionId: $sessionId,
                answerId: $answerId,
                questionText: $answer->init_followup_text,
            ));
        }
    }
}
