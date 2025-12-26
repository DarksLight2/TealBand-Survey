<?php

namespace Tealband\Survey\Events;

use Illuminate\Queue\SerializesModels;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Models\EmployeeSession;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EmployeeAnswerSavedWithoutClarifyingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SurveyResponse $surveyResponse,
    ) { }
}
