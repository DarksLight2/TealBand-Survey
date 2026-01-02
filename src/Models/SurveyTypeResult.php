<?php

namespace Tealband\Survey\Models;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class SurveyTypeResult extends Model
{
    use HasUlids;

    protected $fillable = [
        'org_id',
        'survey_id',
        'milestone_id',
        'subject_id',
        'subject_type',
        'value',
        'summary',
        'type',
    ];

    protected $casts = [
        'type' => SurveyType::class,
    ];
}
