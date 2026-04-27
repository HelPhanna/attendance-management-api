<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'class_id',
        'term_id',
        'teacher_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status',
        'created_on',

    ];

    protected $casts = [
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i",
        "created_on" => "datetime",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    // formart time

    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_time->format('g:i A');
    }

    public function getFormattedEndTimeAttribute(): string
    {
        return $this->end_time->format('g:i A');
    }

    public function getTimeRangeAttribute()
    {
        return $this->getFormattedStartTimeAttribute() . " - " . $this->getFormattedEndTimeAttribute();
    }


    // relationship code

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, "class_id", 'id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class, "term_id", 'id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, "subject_id", 'id');
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, "teacher_id", 'id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, "class_session_id", "id");
    }
}
