<?php

namespace Tealband\Survey\Models;

use Tealband\Survey\Enums\SurveyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasUlids;

    protected $fillable = [
        'title',
        'version',
        'milestone_id',
        'user_id',
        'org_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('tealband-survey.models.user'), 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function org(): BelongsTo
    {
        return $this->belongsTo(config('tealband-survey.models.org'), 'org_id');
    }
}
