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
        'promo_id',
        'order_number',
        'ref_id'
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->order_number = self::getOrderNumber();
        });
    }

    public static function getOrderNumber($num = 1)
    {
        $code = "BK" . str_pad(self::count() + $num, 7, 0, STR_PAD_LEFT);

        if (Booking::where('order_number', $code)->count() > 0) {
            return self::getOrderNumber($num + 1);
        }

        return $code;
    }

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
