<?php

declare(strict_types=1);

namespace Tealband\Survey\Data;

use Tealband\Survey\Enums\SurveyType;

readonly class LimitationDTO
{
    public function __construct(
        public SurveyType $type,
        public int $percentage,
        /** @param $question LimitationQuestionDTO[] */
        public array $questions,
    ) {}
}
