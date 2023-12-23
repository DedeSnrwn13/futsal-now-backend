<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'ground_id',
        'user_id',
        'order_date',
        'started_at',
        'ended_at',
        'total_price',
        'order_status',
        'payment_method',
        'payment_status',
        'paid_at',
        'promo_id'
    ];

    public function ground()
    {
        return $this->belongsTo(Ground::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
