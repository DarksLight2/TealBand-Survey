<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Illuminate\Contracts\Support\Arrayable;

readonly class MilestoneInfoDTO implements Arrayable
{
    public function __construct(
        public int $completedSurveys,
    ) {}

    public function toArray(): array
    {
        return [
            'completed_surveys' => $this->completedSurveys,
        ];
    }
}
