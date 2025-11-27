<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Answer;

use Illuminate\Contracts\Support\Arrayable;

readonly class UpdateAnswerDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public ?string $text = null,
        public ?bool $triggerFollowup = null,
        public ?string $initFollowupText = null,
        public ?int $weight = null,
        public ?string $gptPrompt = null,
        public ?int $questionId = null,
    ) {}


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'trigger_followup' => $this->triggerFollowup,
            'init_followup_text' => $this->initFollowupText,
            'weight' => $this->weight,
            'gpt_prompt' => $this->gptPrompt,
            'question_id' => $this->questionId,
        ];
    }
}
