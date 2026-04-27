<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    protected $fillable = [
        "user_id",
        "first_name",
        "last_name",
        "phone",
        "gender",
        "date_of_birth",
        "address",
        "image"
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
