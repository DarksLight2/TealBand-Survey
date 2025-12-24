<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'employee_session_id',
        'questions',
        'answer_id',
    ];

    protected $casts = [
        'questions' => 'json',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
