<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ground extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_arena_id',
        'name',
        'description',
        'rental_price',
        'capacity',
        'is_available',
        'image'
    ];

    protected $casts = [
        'image' => 'array'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function groundReviews()
    {
        return $this->hasMany(GroundReview::class);
    }

    public function sportArena()
    {
        return $this->belongsTo(SportArena::class);
    }
}
