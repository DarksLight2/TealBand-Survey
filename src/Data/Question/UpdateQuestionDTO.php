<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class UpdateQuestionDTO implements Arrayable
{
    public function __construct(
        public ?string $text = null,
        public ?string $surveyId = null,
        public ?string $language = null,
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'survey_id' => $this->surveyId,
            'language' => $this->language,
        ];
    }
}
