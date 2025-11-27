<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Contracts\Support\Arrayable;

readonly class CreateSurveyDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public SurveyType $type,
        public ?string $userId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'user_id' => $this->userId,
        ];
    }
}
