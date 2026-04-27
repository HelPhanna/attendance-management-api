<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;
    // protected $table = 'teacher';
    protected $fillable = [
        "user_id",
        "teacher_code",
        "status"
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            Classes::class,
            'class_teacher',
            'teacher_id',
            'class_id'
        );
    }
    public function classSections(): HasMany
    {
        return $this->hasMany(ClassSession::class, 'teacher_id');
    }
    // public function attendencesRecords():HasMany
    // {
    //     return $this->hasMany(AttendenceRecord::class, 'recorded_by', 'user_id');
    // }
}
