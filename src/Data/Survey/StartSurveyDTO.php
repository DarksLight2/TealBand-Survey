<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Contracts\Support\Arrayable;

readonly class StartSurveyDTO implements Arrayable
{
    public function __construct(
        public string $surveyId,
        public int $milestone,
    ) {}

    public function toArray(): array
    {
        return [
            
        ];
    }
}
