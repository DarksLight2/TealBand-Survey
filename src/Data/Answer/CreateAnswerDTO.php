<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Answer;

use Illuminate\Contracts\Support\Arrayable;

readonly class CreateAnswerDTO implements Arrayable
{
    public function __construct(
        public string $text,
        public bool $triggerFollowup,
        public string $initFollowupText,
        public int $weight,
        public string $gptPrompt,
        public int $questionId,
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'init_followup_text' => $this->initFollowupText,
            'trigger_followup' => $this->triggerFollowup,
            'gpt_prompt' => $this->gptPrompt,
            'weight' => $this->weight,
            'question_id' => $this->questionId,
        ];
    }
}
