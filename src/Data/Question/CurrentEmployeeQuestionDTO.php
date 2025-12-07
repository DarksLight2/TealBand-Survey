<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class CurrentEmployeeQuestionDTO implements Arrayable
{
    public function __construct(
        public int $id,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
