<?php

namespace Tealband\Survey\Providers;

use Illuminate\Support\ServiceProvider;
use TealBand\Survey\Services\SurveyService;
use Tealband\Survey\Services\AnswerService;
use Tealband\Survey\Contracts\AnswerServiceContract;

class SurveyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('survey', function() {
            return new SurveyService();
        });
        $this->app->singleton(AnswerServiceContract::class, function() {
            return new AnswerService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations/');
        $this->publishes([
            __DIR__ . '/../../config/tealband-survey.php' => config_path('tealband-survey.php'),
        ]);
    }
}
