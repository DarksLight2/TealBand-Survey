<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasUlids;

    protected $fillable = [
        'prompt',
        'weight',
        'clarifying',
        'question_id',
        'survey_id',
        'org_id',
    ];

    protected $casts = [
        'weight' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
