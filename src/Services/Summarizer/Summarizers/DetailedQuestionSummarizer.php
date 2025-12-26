<?php

namespace Tealband\Survey\Services\Summarizer\Summarizers;

use Illuminate\Support\Collection;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Enums\SurveyResponseStatus;

class DetailedQuestionSummarizer
{
    public function handle(string $questionId): string|null
    {
        $question = Question::query()->find($questionId);

        if(! $question) return null;

        $responses = SurveyResponse::query()
            ->with(['question', 'answer'])
            ->where([
                'question_id' => $questionId,
                'status' => SurveyResponseStatus::Closed->value,
            ])
            ->get();

        $data = json_encode($this->prepareData($responses), JSON_UNESCAPED_UNICODE);

        $prompts = [
            ['role' => 'user', 'content' => $data],
        ];

        $result = AiService::questionSummarizer()->handle(array_merge(config('tealband-survey.summarizers.detailed-question.prompts'), $prompts));

        return empty($result) ? null : $result;
    }

    private function prepareData(Collection $responses): array
    {
        return $responses->map(fn (SurveyResponse $response) => [
            'summary' => $response->summary,
            'answer weight' => $response->answer->weight,
            'question title' => $response->question->title,
        ])->toArray();
    }
}
