<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Tealband\Survey\Enums\SurveyResponseStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasUlids;

    protected $fillable = [
        'employee_session_id',
        'answer_id',
        'question_id',
        'survey_id',
        'user_id',
        'milestone_id',
        'org_id',
        'comment',
        'ai_clarifying',
        'response',
        'summary',
        'status',
    ];

    protected $casts = [
        'status' => SurveyResponseStatus::class,
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
