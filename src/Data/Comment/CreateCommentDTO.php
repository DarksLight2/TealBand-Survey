<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Comment;

use Illuminate\Contracts\Support\Arrayable;

readonly class CreateCommentDTO implements Arrayable
{
    public function __construct(
        public string $employeeSessionId,
        public int $answerId,
        public string $questionText,
    ) {}

    public function toArray(): array
    {
        return [
            'employee_session_id' => $this->employeeSessionId,
            'answer_id' => $this->answerId,
            'question_text' => $this->questionText,
        ];
    }
}
