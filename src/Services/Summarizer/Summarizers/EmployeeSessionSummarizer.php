<?php

namespace Tealband\Survey\Services\Summarizer\Summarizers;

use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Enums\EmployeeSessionStatus;

class EmployeeSessionSummarizer
{
    public function handle(string $sessionId): string|null
    {
        $session = EmployeeSession::query()
            ->with(['surveyResponse'])
            ->where('status', EmployeeSessionStatus::Finished->value)
            ->find($sessionId);

        if(! $session) return null;

        $data = json_encode($this->prepareData($session), JSON_UNESCAPED_UNICODE);

        $prompts = [
            ['role' => 'user', 'content' => $data],
        ];

        $result = AiService::employeeSessionSummarizer()->handle(array_merge(config('tealband-survey.summarizers.employee-session.prompts'), $prompts));

        return empty($result) ? null : $result;
    }

    private function prepareData(EmployeeSession $session): array
    {
        return $session->surveyResponse->map(fn (SurveyResponse $surveyResponse) => [
            'summary' => $surveyResponse->summary,
        ])->toArray();
    }
}
