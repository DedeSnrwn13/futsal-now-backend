<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unique_code',
        'type',
        'amount',
        'started_at',
        'ended_at',
        'status'
    ];
}
