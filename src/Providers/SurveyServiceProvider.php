<?php

namespace Tealband\Survey\Providers;

use Event;
use Illuminate\Support\ServiceProvider;
use Tealband\Survey\Services\SurveyService;
use Tealband\Survey\Services\AnswerService;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Events\SavedClarifyResponseEvent;
use Tealband\Survey\Events\SurveyQuestionsIsEndEvent;
use Tealband\Survey\Services\ClarifyingQuestionService;
use Tealband\Survey\Contracts\SummarizerServiceContract;
use Tealband\Survey\Services\Summarizer\SummarizerService;
use Tealband\Survey\Events\EmployeeSessionIsFinishedEvent;
use Tealband\Survey\Listeners\FinishEmployeeSessionListener;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;
use Tealband\Survey\Listeners\GenerateEmployeeAnswerSummaryListener;
use Tealband\Survey\Listeners\GenerateEmployeeSessionSummaryListener;
use Tealband\Survey\Events\EmployeeAnswerSavedWithoutClarifyingEvent;

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
        $this->app->singleton(SummarizerServiceContract::class, function() {
            return new SummarizerService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../migrations/');
        $this->publishes([
            __DIR__ . '/../../config/tealband-survey.php' => config_path('tealband-survey.php'),
        ]);

        Event::listen(
            EmployeeAnswerSavedWithoutClarifyingEvent::class,
            [GenerateEmployeeAnswerSummaryListener::class, 'handle']
        );

        Event::listen(
            SavedClarifyResponseEvent::class,
            [GenerateEmployeeAnswerSummaryListener::class, 'handle']
        );

        Event::listen(
            EmployeeSessionIsFinishedEvent::class,
            [GenerateEmployeeSessionSummaryListener::class, 'handle']
        );

        Event::listen(
            SurveyQuestionsIsEndEvent::class,
            [FinishEmployeeSessionListener::class, 'handle']
        );
    }
}
