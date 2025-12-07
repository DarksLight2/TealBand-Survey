<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Illuminate\Contracts\Support\Arrayable;
use Tealband\Survey\Data\Question\QuestionDTO;

readonly class AnalyticDTO implements Arrayable
{
    public function __construct(
        public int $mainPointer,
        /** @param $questions AnalyticQuestionDTO[] */
        public array $questions,
    ) {}

    public function toArray(): array
    {
        return [
            'main_pointer' => $this->mainPointer,
            'questions' => array_map(fn(QuestionDTO $question) => $question->toArray(), $this->questions),
        ];
    }
}
