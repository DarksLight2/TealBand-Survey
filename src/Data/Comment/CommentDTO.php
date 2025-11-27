<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Comment;

use Illuminate\Contracts\Support\Arrayable;

readonly class CommentDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public string $employeeSessionId,
        public string $questionText,
        public int $answerId,
        public ?string $answerText = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_session_id' => $this->employeeSessionId,
            'question_text' => $this->questionText,
            'answer_id' => $this->answerId,
            'answer_text' => $this->answerText,
        ];
    }
}
