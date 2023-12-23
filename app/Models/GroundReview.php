<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'ground_id',
        'user_id',
        'rate',
        'comment',
    ];

    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
