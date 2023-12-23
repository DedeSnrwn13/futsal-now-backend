<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ground extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'rental_price',
        'capacity',
        'is_available',
        'open_time',
        'close_time',
        'image'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function groundReviews()
    {
        return $this->hasMany(GroundReview::class);
    }
}
