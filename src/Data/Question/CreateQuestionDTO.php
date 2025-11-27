<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Question;

use Illuminate\Contracts\Support\Arrayable;

readonly class CreateQuestionDTO implements Arrayable
{
    public function __construct(
        public string $text,
        public string $surveyId,
        public string $language,
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
