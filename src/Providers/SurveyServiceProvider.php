<?php

namespace Tealband\Survey\Providers;

use Illuminate\Support\ServiceProvider;
use Tealband\Survey\Services\SurveyService;
use Tealband\Survey\Services\AnswerService;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Services\ClarifyingQuestionService;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;

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
        $this->app->singleton(ClarifyingQuestionServiceContract::class, function() {
            return new ClarifyingQuestionService();
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
