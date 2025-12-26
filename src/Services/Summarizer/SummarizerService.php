<?php

namespace Tealband\Survey\Services\Summarizer;

use Tealband\Survey\Enums\SurveyType;
use Tealband\Survey\Contracts\SummarizerServiceContract;
use Tealband\Survey\Services\Summarizer\Enums\QuestionSummarizerType;
use Tealband\Survey\Services\Summarizer\Summarizers\QuestionSummarizer;
use Tealband\Survey\Services\Summarizer\Summarizers\QuestionsTypeSummarizer;
use Tealband\Survey\Services\Summarizer\Enums\CommonEmployeesSummarizerType;
use Tealband\Survey\Services\Summarizer\Summarizers\EmployeeAnswerSummarizer;
use Tealband\Survey\Services\Summarizer\Summarizers\EmployeeSessionSummarizer;
use Tealband\Survey\Services\Summarizer\Summarizers\CommonEmployeesSummarizer;
use Tealband\Survey\Services\Summarizer\Summarizers\DetailedQuestionSummarizer;

class SummarizerService implements SummarizerServiceContract
{
    public function sessionSummary(string $sessionId): string|null
    {
        return (new EmployeeSessionSummarizer())->handle($sessionId);
    }

    public function employeeAnswerSummary(string $surveyResponseId): string|null
    {
        return (new EmployeeAnswerSummarizer())->handle($surveyResponseId);
    }

    public function commonEmployeesSummary(array $employeeIds, string $orgId, CommonEmployeesSummarizerType $type): string|null
    {
        return (new CommonEmployeesSummarizer())->handle($employeeIds, $orgId, $type);
    }

    public function questionTypeSummary(array $employeeIds, string $orgId, SurveyType $surveyType, QuestionSummarizerType $summarizerType): string|null
    {
        return (new QuestionsTypeSummarizer())->handle($employeeIds, $orgId, $surveyType, $summarizerType);
    }

    public function questionSummary(string $questionId): string|null
    {
        return (new QuestionSummarizer())->handle($questionId);
    }

    public function detailedQuestionSummary(string $questionId): string|null
    {
        return (new DetailedQuestionSummarizer())->handle($questionId);
    }
}
