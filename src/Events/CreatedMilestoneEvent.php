<?php

namespace Tealband\Survey\Events;

use Tealband\Survey\Models\Milestone;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreatedMilestoneEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Milestone $milestone,
    ) { }
}
