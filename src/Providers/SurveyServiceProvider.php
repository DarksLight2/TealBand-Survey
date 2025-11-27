<?php

namespace Tealband\Survey\Providers;

use Illuminate\Support\ServiceProvider;

class SurveyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations/');
        $this->publishes([
            __DIR__ . '/../../config/tealband-survey.php' => config_path('tealband-survey.php'),
        ]);
    }
}
