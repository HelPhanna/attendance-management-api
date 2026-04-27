<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Validation for start and end date
    protected static function booted(): void
    {
        static::saving(function (Term $term) {
            if ($term->start_date && $term->end_date && $term->start_date->gt($term->end_date)) {
                throw ValidationException::withMessages([
                    'end_date' => ['The end date must be on or after the start date.'],
                ]);
            }

            if ($term->academicYear) {
                $year = $term->academicYear;

                if ($term->start_date && $term->start_date->lt($year->start_date)) {
                    throw ValidationException::withMessages([
                        'start_date' => ['The term start date must not be before the academic year start date.'],
                    ]);
                }

                if ($term->end_date && $term->end_date->gt($year->end_date)) {
                    throw ValidationException::withMessages([
                        'end_date' => ['The term end date must not be after the academic year end date.'],
                    ]);
                }
            }
        });
    }

    // Relationships

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function class_session(): HasMany {
        return $this->hasMany(ClassSession::class, 'term_id');
    }

    public function black_list(): HasMany {
        return $this->hasMany(BlackList::class, 'term_id');
    }

}
