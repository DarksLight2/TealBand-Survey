<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Tealband\Survey\Enums\EmployeeSessionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSession extends Model
{
    use HasUlids;

    protected $fillable = [
        'survey_id',
        'org_id',
        'milestone_id',
        'user_id',
        'status',
        'comment',
        'summary',
    ];

    protected $casts = [
        'status' => EmployeeSessionStatus::class,
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function surveyResponse(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
