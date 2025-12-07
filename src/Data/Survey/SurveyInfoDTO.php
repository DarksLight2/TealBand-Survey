<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Illuminate\Contracts\Support\Arrayable;

readonly class SurveyInfoDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public int $engagementIndex,
        public int $currentMilestone,
        public string $answersSummary,
        /** @param $milestones MilestoneInfoDTO[] */
        public array $milestones,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'engagementIndex' => $this->engagementIndex,
            'currentMilestone' => $this->currentMilestone,
            'answersSummary' => $this->answersSummary,
            'milestones' => array_map(fn ($m) => $m->toArray(), $this->milestones),
        ];
    }
}
