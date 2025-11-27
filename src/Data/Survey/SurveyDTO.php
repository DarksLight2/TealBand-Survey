<?php

declare(strict_types=1);

namespace Tealband\Survey\Data\Survey;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Contracts\Support\Arrayable;

readonly class SurveyDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
        public SurveyType $type,
        public ?string $userId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'user_id' => $this->userId,
        ];
    }
}
