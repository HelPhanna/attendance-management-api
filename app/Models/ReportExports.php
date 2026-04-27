<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportExports extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'report_type',
        'file_type',
        'status',
        'file_path',
        'file_size_kb',
        'filters',
        'exported_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'exported_at' => 'datetime',
    ];

    // relationship

    public function user():BelongsTo {
                    // belongsTo in model that have the same record many time 
                    //(Ex one user can generate multi RE)
        return $this->belongsTo(User::class , 'user_id' , 'id');
                            //Class           FK of RE    PK of USER 
    }
    public function classes():BelongsTo {
        return $this->belongsTo(Classes::class , 'class_id' , 'id');
    }
}
