<?php

namespace Tealband\Survey\Models;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasUlids;

    protected $fillable = [
        'title',
        'survey_id',
        'org_id',
        'keywords',
        'type',
    ];

    protected $casts = [
        'keywords' => 'json:unicode',
        'type' => SurveyType::class,
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function org(): BelongsTo
    {
        return $this->belongsTo(config('tealband-survey.models.org'), 'org_id');
    }
}
