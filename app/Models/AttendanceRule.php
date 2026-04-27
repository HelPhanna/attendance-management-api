<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'late_threshold_minutes',
        'absent_threshold_minutes',
        'exclude_weekends',
        'auto_exclude_public_holidays',
    ];

    protected $casts = [
        'exclude_weekends' => 'boolean',
        'auto_exclude_public_holidays' => 'boolean',
    ];
}
