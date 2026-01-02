<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class SurveyQuestionResult extends Model
{
    use HasUlids;

    protected $fillable = [
        'org_id',
        'survey_id',
        'milestone_id',
        'question_id',
        'value',
        'summary',
        'report',
    ];
}
