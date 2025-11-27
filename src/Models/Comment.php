<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'employee_session_id',
        'question_text',
        'answer_text',
        'answer_id',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
