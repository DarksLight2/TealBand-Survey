<?php

namespace Tealband\Survey\Models;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    protected $fillable = [
        'name',
        'type',
        'user_id',
    ];

    protected $casts = [
        'type' => SurveyType::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('tealband-survey.models.user'));
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
