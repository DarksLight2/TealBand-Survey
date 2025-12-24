<?php

namespace Tealband\Survey\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    use HasUlids;

    protected $fillable = [
        'org_id',
        'survey_id',
        'value',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
