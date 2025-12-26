<?php

namespace Tealband\Survey\Listeners;

use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Enums\SurveyResponseStatus;
use Tealband\Survey\Events\SavedClarifyResponseEvent;
use Tealband\Survey\Events\EmployeeAnswerSavedWithoutClarifyingEvent;

class GenerateEmployeeAnswerSummaryListener
{
    public function handle(SavedClarifyResponseEvent|EmployeeAnswerSavedWithoutClarifyingEvent $event): void
    {
        $event->surveyResponse->summary = Survey::summarizer()->employeeAnswerSummary($event->surveyResponse->id);
        $event->surveyResponse->save();
    }
}
