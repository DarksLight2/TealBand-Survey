<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Enums\SurveyType;
use Tealband\Survey\Data\LimitationDTO;

interface SummaryServiceContract
{
    /** @return LimitationDTO[] */
    public function limitations(): array;

    public function generateForEmployee(int|string $employeeId, int $milestone): string;
    public function generateForDepartment(int|string $departmentId, int $milestone): string;
    public function generateForUser(int|string $employeeId, int $milestone): string;
    public function generateForAnswers(int $milestone): string;

    public function employeeHealth(int|string $employeeId, int $milestone): int;
    public function surveyTypeHealth(SurveyType $surveyType, int|string $userId, int $milestone): int;
}
