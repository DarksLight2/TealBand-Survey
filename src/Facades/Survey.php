<?php

declare(strict_types=1);

namespace Tealband\Survey\Facades;

use Illuminate\Support\Facades\Facade;

class Survey extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'survey';
    }
}
