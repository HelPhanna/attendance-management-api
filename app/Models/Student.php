<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// enum for student status

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
}

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_code',
        'status',
    ];

    protected $casts = [
        'status' => StudentStatus::class,
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class, 'student_id');
    }

    public function attendance_record(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function black_list(): HasMany
    {
        return $this->hasMany(BlackList::class, 'student_id');
    }

    public function classes()
    {
        return $this->belongsToMany(
            Classes::class,
            'enrollment',
            'student_id',
            'class_id'
        );
    }
}
