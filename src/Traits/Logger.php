<?php

declare(strict_types=1);

namespace Tealband\Survey\Traits;

use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\Log;

trait Logger
{
    protected function logger(): LoggerInterface
    {
        return Log::channel();
    }
}
