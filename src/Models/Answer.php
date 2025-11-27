<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = [
        'text',
        'init_followup_text',
        'trigger_followup',
        'gpt_prompt',
        'weight',
        'question_id',
    ];

    protected $casts = [
        'trigger_followup' => 'boolean',
        'weight' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
