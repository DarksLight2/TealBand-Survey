<?php

namespace Tealband\Survey\Events;

use Illuminate\Queue\SerializesModels;
use Tealband\Survey\Models\SurveyResponse;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SavedClarifyResponseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SurveyResponse $surveyResponse,
    ) { }
}
