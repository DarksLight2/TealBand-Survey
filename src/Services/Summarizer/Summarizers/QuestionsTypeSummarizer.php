<?php

namespace Tealband\Survey\Services\Summarizer\Summarizers;

use Illuminate\Support\Collection;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Models\Milestone;
use Tealband\Survey\Enums\SurveyType;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Enums\SurveyResponseStatus;
use Tealband\Survey\Events\GeneratedQuestionsTypeTeamSummaryEvent;
use Tealband\Survey\Events\GeneratedQuestionsTypeUserSummaryEvent;
use Tealband\Survey\Services\Summarizer\Enums\QuestionSummarizerType;
use Tealband\Survey\Events\GeneratedQuestionsTypeOrganizationSummaryEvent;

class QuestionsTypeSummarizer
{
    public function handle(array $employeeIds, string $orgId, SurveyType $surveyType, QuestionSummarizerType $summarizerType): string|null
    {
        if(empty($employeeIds)) return null;

        $milestone = Milestone::query()->where('org_id', $orgId)->latest()->first();

        if(! $milestone) return null;

        $questions = Question::query()
            ->where('org_id', $orgId)
            ->where('type', $surveyType)
            ->pluck('id')
            ->toArray();

        $sessions = EmployeeSession::query()
            ->where('milestone_id', $milestone->id)
            ->whereHas('surveyResponse', fn($q) => $q->where('status', SurveyResponseStatus::Closed->value)->whereIn('question_id', $questions))
            ->whereIn('user_id', $employeeIds)
            ->get();

        $data = json_encode(array_merge($this->prepareData($sessions), ['Question type' => $surveyType->name]), JSON_UNESCAPED_UNICODE);

        $prompts = [
            ['role' => 'user', 'content' => $data],
        ];

        $result = AiService::questionsTypeSummarizer()->handle(array_merge(config('tealband-survey.summarizers.questions-type.prompts'), $prompts));

        match ($summarizerType) {
            QuestionSummarizerType::Organization => event(new GeneratedQuestionsTypeOrganizationSummaryEvent($result)),
            QuestionSummarizerType::Team => event(new GeneratedQuestionsTypeTeamSummaryEvent($result)),
            QuestionSummarizerType::User => event(new GeneratedQuestionsTypeUserSummaryEvent($result)),
        };

        return empty($result) ? null : $result;
    }

    private function prepareData(Collection $sessions): array
    {
        return $sessions->map(fn (EmployeeSession $session) => [
            'summary' => $session->summary,
        ])->toArray();
    }
}
