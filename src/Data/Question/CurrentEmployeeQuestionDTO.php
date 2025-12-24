<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class CurrentEmployeeQuestionDTO implements Arrayable
{
    public function __construct(
        public string $id,
        public string $text,
        /** @param $answers CurrentEmployeeQuestionAnswerDTO[] */
        public array $answers,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'answers' => array_map(fn(CurrentEmployeeQuestionAnswerDTO $answer) => $answer->toArray(), $this->answers),
        ];
    }
}
