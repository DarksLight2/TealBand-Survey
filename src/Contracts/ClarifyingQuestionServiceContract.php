<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Data\Survey\SurveyDTO;
use Tealband\Survey\Data\Comment\CommentDTO;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;

interface ClarifyingQuestionServiceContract
{
    /**
     * Create and return next question for employee
     */
    public function generate(string $employeeSessionId): string;

    /**
     * Attach user answer to current question
     */
    public function userAnswer(string $employeeSessionId, string $answer): void;

    public function comment(string $employeeSessionId, string $comment): void;
}
