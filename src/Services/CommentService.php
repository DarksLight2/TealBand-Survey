<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Comment;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Data\Comment\CommentDTO;
use Tealband\Survey\Data\Comment\CreateCommentDTO;
use Tealband\Survey\Data\Comment\UpdateCommentDTO;

/**
 * @template-extends CRUD<CreateCommentDTO, CommentDTO, UpdateCommentDTO, Comment>
 */
class CommentService
{
    use CRUD;

    protected string $model = Comment::class;
    protected string $baseDTO = CommentDTO::class;

    /**
     * For new records or updating exists
     */
    public function userAnswer(int $commentId, string $employeeSessionId, string $answerText, bool $aiQuestionGeneration = true): void
    {
        $this->update($commentId, new UpdateCommentDTO(
            answerText: $answerText,
        ));

        $comment = Comment::query()
            ->with(['answer'])
            ->where('id', $commentId)
            ->first();

        $answerComments = Comment::query()
            ->where('answer_id', $comment->answer_id)
            ->where('employee_session_id', $employeeSessionId)
            ->get();

        if($answerComments->count() < 3 && $aiQuestionGeneration) {
            $answersPrompt = '';

            foreach($answerComments as $key => $answerComment) {
                if($answerComment->id === $commentId) continue;

                $answersPrompt .= "Question $key: $answerComment->question_text" . PHP_EOL;
                $answersPrompt .= "User answer $key: $answerComment->answer_text" . PHP_EOL;
            }

            $questionText = AiService::make()->handle("{$comment->answer->gpt_prompt} $answersPrompt");

            $this->create(new CreateCommentDTO(
                employeeSessionId: $employeeSessionId,
                answerId: $comment->answer_id,
                questionText: $questionText,
            ));
        }
    }
}
