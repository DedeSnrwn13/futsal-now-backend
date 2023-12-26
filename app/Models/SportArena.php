<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportArena extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'email',
        'city',
        'district',
        'address',
        'wa_number',
        'open_time',
        'close_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grounds()
    {
        return $this->hasMany(Ground::class);
    }
}