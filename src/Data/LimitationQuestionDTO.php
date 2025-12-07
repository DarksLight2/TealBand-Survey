<?php

declare(strict_types=1);

namespace Tealband\Survey\Data;

readonly class LimitationQuestionDTO
{
    public function __construct(
        public string $text,
        public int $pointer,
    ) {}
}
