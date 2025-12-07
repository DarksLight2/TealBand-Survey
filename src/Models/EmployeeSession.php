<?php

namespace Tealband\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSession extends Model
{
    use HasUuids;

    protected $fillable = [
        'milestone',
        'department_id',
        'department_type',
        'employee_id',
        'employee_type',
        'survey_id',
        'status', // завершил или нет
    ];

    public function department(): MorphTo
    {
        return $this->morphTo();
    }

    public function employee(): MorphTo
    {
        return $this->morphTo();
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
