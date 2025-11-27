<?php

namespace Tealband\Survey\Services\AI\Contracts;

interface AiHandlerContract
{
    public function handle(string $prompt): string;
}
