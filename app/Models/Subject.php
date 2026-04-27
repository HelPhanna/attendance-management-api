<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    // Relationships

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'subject_id');
    }

    public function gradeLevels(): BelongsToMany
    {
        return $this->belongsToMany(
            GradeLevel::class,
            'grade_level_subjects',
            'subject_id',
            'grade_level_id'
        )->withTimestamps();
    }
}
