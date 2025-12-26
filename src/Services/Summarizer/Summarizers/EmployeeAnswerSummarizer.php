<?php

namespace Tealband\Survey\Services\Summarizer\Summarizers;

use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Services\AI\AiService;

class EmployeeAnswerSummarizer
{
    public function handle(string $surveyResponseId): string|null
    {
        $surveyResponse = SurveyResponse::with([
            'question',
            'answer',
        ])->find($surveyResponseId);

        $data = json_encode($this->prepareData($surveyResponse), JSON_UNESCAPED_UNICODE);

        $prompts = [
            ['role' => 'user', 'content' => $data],
        ];

        $result = AiService::employeeSessionSummarizer()->handle(array_merge(config('tealband-survey.summarizers.employee-answer.prompts'), $prompts));

        return empty($result) ? null : $result;
    }

    private function prepareData(SurveyResponse $surveyResponse): array
    {
        return [
            'Question title' => $surveyResponse->question->title,
            'Answer weight' => $surveyResponse->answer->weight,
            'First clarifying question' => $surveyResponse->answer->clarifying,
            'Second clarifying question' => $surveyResponse->ai_clarifying,
            'First clarifying answer' => $surveyResponse->comment,
            'Second clarifying answer' => $surveyResponse->response,
        ];
    }
}
