<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Data\LimitationDTO;
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

    public function getCurrentEmployeeQuestion(string $sessionId): CurrentEmployeeQuestionDTO|null;

    public function hasCompletedForEmployee(string $surveyId, string $userId, string $milestoneId): bool;

    public function hasActiveForEmployee(string $surveyId, string $userId, string $milestoneId): bool;

    public function createMilestone(string $surveyId, string $orgId, int $value): string;

    public function getAllForUser(int|string $userId): array;

    /** ON FUTURE */
    public function createFromTemplate();

    public function getInfo(int $surveyId): SurveyInfoDTO;

    public function analytic(string $milestone): AnalyticDTO;

    /**
     * @return LimitationDTO[]
     */
    public function getLimitations(int|string $userId): array;

    /**
     * Return survey session id for current employee
     */
    public function newEmployeeSession(
        string $milestoneId,
        string $surveyId,
        string $userId,
        string $orgId,
    ): string;
}
