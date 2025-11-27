<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSessionAnswer extends Model
{
    protected $fillable = [
        'employee_session_id',
        'answer_id',
        'question_id',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function employeeSession(): BelongsTo
    {
        return $this->belongsTo(EmployeeSession::class);
    }
}
