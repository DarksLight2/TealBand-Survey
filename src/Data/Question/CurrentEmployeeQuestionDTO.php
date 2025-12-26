<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class CurrentEmployeeQuestionDTO implements Arrayable
{
    public function __construct(
        public string $id,
        public string $text,
        public array $keywords,
        /** @param $answers CurrentEmployeeQuestionAnswerDTO[] */
        public array $answers,
        public int $currentQuestionIndex,
        public int $amountQuestions,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'keywords' => $this->keywords,
            'answers' => array_map(fn(CurrentEmployeeQuestionAnswerDTO $answer) => $answer->toArray(), $this->answers),
        ];
    }
}
