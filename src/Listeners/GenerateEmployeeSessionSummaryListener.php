<?php

namespace Tealband\Survey\Listeners;

use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Events\EmployeeSessionIsFinishedEvent;
use Tealband\Survey\Events\GeneratedEmployeeSessionSummaryEvent;

class GenerateEmployeeSessionSummaryListener
{
    public function handle(EmployeeSessionIsFinishedEvent $event): void
    {
        $event->session->summary = Survey::summarizer()->sessionSummary($event->session->id);
        $event->session->save();

        event(new GeneratedEmployeeSessionSummaryEvent());
    }
}
