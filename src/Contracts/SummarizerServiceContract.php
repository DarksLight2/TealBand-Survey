<?php

declare(strict_types=1);

namespace Tealband\Survey\Contracts;

use Tealband\Survey\Enums\SurveyType;
use Tealband\Survey\Services\Summarizer\Enums\QuestionSummarizerType;
use Tealband\Survey\Services\Summarizer\Enums\CommonEmployeesSummarizerType;

interface SummarizerServiceContract
{

    public function sessionSummary(string $sessionId): string|null;

    public function employeeAnswerSummary(string $surveyResponseId): string|null;

    public function commonEmployeesSummary(array $employeeIds, string $orgId, CommonEmployeesSummarizerType $type): string|null;

    public function questionTypeSummary(array $employeeIds, string $orgId, SurveyType $surveyType, QuestionSummarizerType $summarizerType): string|null;

    public function questionSummary(string $questionId): string|null;

    public function detailedQuestionSummary(string $questionId): string|null;
}
