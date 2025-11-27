<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Comment;

use Illuminate\Contracts\Support\Arrayable;

readonly class UpdateCommentDTO implements Arrayable
{
    public function __construct(
        public ?string $employeeSessionId = null,
        public ?string $questionText = null,
        public ?int $answerId = null,
        public ?string $answerText = null,
    ) {}

    public function toArray(): array
    {
        return [
            'employee_session_id' => $this->employeeSessionId,
            'question_text' => $this->questionText,
            'answer_id' => $this->answerId,
            'answer_text' => $this->answerText,
        ];
    }
}
