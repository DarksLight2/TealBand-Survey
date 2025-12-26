<?php

namespace Tealband\Survey\Events;

use Illuminate\Queue\SerializesModels;
use Tealband\Survey\Models\EmployeeSession;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EmployeeSessionIsFinishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public EmployeeSession $session,
    ) { }
}
