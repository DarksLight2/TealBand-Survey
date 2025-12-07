<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Data\LimitationDTO;
use Tealband\Survey\Data\Survey\SurveyDTO;
use Tealband\Survey\Data\Survey\AnalyticDTO;
use Tealband\Survey\Data\Survey\SurveyInfoDTO;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;

interface SurveyServiceContract
{
    /** Access to answer business logic */
    public function answer(): AnswerServiceContract;
    /** Access to summary business logic */
    public function summary(): SummaryServiceContract;
    /** Access to clarifying question business logic */
    public function clarifyingQuestion(): ClarifyingQuestionServiceContract;

    public function getCurrentEmployeeQuestion(): CurrentEmployeeQuestionDTO|null;

    public function hasCompletedForEmployee(int|string $employeeId, int $surveyId): bool;

    public function hasActiveForEmployee(int|string $employeeId): SurveyDTO|bool;

    public function getAllForUser(int|string $userId): array;

    /** ON FUTURE */
    public function createFromTemplate();

    public function getInfo(int $surveyId): SurveyInfoDTO;

    public function analytic(int $milestone): AnalyticDTO;

    /**
     * @return LimitationDTO[]
     */
    public function getLimitations(int|string $userId): array;

    /**
     * Return survey session id for current employee
     */
    public function newEmployeeSession(
        int $milestone,
        int $surveyId,
        int|string $employeeId,
        int|string|null $departmentId,
    ): string;
}
