<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use App\Models\Enrollment;

class GradeLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        "code",
        "name",
        "order_no",
        "is_active"
    ];
    // public function gradeLevelSubjects():HasMany
    // {
    //     return $this->hasMany(GradeLevelSubject::class, 'grade_level_id');
    // }
    public function enrollment(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'grade_level_id');
    }
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'grade_level_id');
    }
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'grade_level_subjects',
            'grade_level_id',
            'subject_id'
        )->withTimestamps();
    }
}
