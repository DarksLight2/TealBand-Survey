<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Tealband\Survey\Data\Answer\AnswerDTO;
use Illuminate\Contracts\Support\Arrayable;

readonly class AnalyticQuestionDTO implements Arrayable
{
    public function __construct(
        public string $text,
        public string $keywords,
        public int $pointer,
        public string $summary,
        public int $answersAmount,
        /** @param $answers AnalyticQuestionAnswerDTO[] */
        public array $answers,
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'keywords' => $this->keywords,
            'pointer' => $this->pointer,
            'summary' => $this->summary,
            'answers_amount' => $this->answersAmount,
            'answers' => array_map(fn(AnswerDTO $answer) => $answer->toArray(), $this->answers),
        ];
    }
}
