<?php

namespace Tealband\Survey\Services\Summarizer\Summarizers;

use Illuminate\Support\Collection;
use Tealband\Survey\Models\Milestone;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Enums\EmployeeSessionStatus;
use Tealband\Survey\Events\GeneratedCommonTeamSummaryEvent;
use Tealband\Survey\Events\GeneratedCommonOrganizationSummaryEvent;
use Tealband\Survey\Services\Summarizer\Enums\CommonEmployeesSummarizerType;

class CommonEmployeesSummarizer
{
    /**
     * @event GeneratedCommonOrganizationSummaryEvent
     * @event GeneratedCommonTeamSummaryEvent
     */
    public function handle(array $employeeIds, string $orgId, CommonEmployeesSummarizerType $type): string|null
    {
        if(empty($employeeIds)) return null;

        $milestone = Milestone::query()->where('org_id', $orgId)->latest()->first();

        if(! $milestone) return null;

        $sessions = EmployeeSession::query()
            ->where('milestone_id', $milestone->id)
            ->where('status', EmployeeSessionStatus::Finished->value)
            ->whereIn('user_id', $employeeIds)
            ->get();

        $data = json_encode(array_merge($this->prepareData($sessions), ['group_type' => $type->name]), JSON_UNESCAPED_UNICODE);

        $prompts = [
            ['role' => 'user', 'content' => $data],
        ];

        $result = AiService::commonEmployeesSummarizer()->handle(array_merge(config('tealband-survey.summarizers.common-employees.prompts'), $prompts));

        match ($type) {
            CommonEmployeesSummarizerType::Organization => event(new GeneratedCommonOrganizationSummaryEvent($result)),
            CommonEmployeesSummarizerType::Team => event(new GeneratedCommonTeamSummaryEvent($result)),
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
