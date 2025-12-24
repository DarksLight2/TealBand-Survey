<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
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
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}

//comment - комментарий пользователя на уточняющий вопрос.
//ai_clarifying - вопрос который сгенерировала модель.
//response - ответ пользователя на этот вопрос.
//summary - итоговое резюме по этому вопросу.
