<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Illuminate\Contracts\Support\Arrayable;

readonly class AnalyticQuestionAnswerDTO implements Arrayable
{
    public function __construct(
        public string $answer,
        public int $amount,
    ) {}

    public function toArray(): array
    {
        return [
            'answer' => $this->answer,
            'amount' => $this->amount,
        ];
    }
}
