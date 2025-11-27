<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Illuminate\Contracts\Support\Arrayable;

readonly class UpdateSurveyDTO implements Arrayable
{
    public function __construct(
        public ?string $name = null,
        public ?string $language = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'language' => $this->language,
        ];
    }
}
