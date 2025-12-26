<?php

namespace Tealband\Survey\Listeners;

use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Events\SurveyQuestionsIsEndEvent;

class FinishEmployeeSessionListener
{
    public function handle(SurveyQuestionsIsEndEvent $event): void
    {
        Survey::markSessionIsFinished($event->session);
    }
}
