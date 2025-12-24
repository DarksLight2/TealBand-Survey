<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Data\Survey\SurveyDTO;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;

interface AnswerServiceContract
{
    public function employeeAnswer(string $answerId, string $sessionId): void;
}
