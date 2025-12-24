<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class CurrentEmployeeQuestionAnswerDTO implements Arrayable
{
    public function __construct(
        public string $id,
        public string $clarifying,
        public int $weight,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'clarifying' => $this->clarifying,
            'weight' => $this->weight,
        ];
    }
}
