<?php

namespace App\Http\Controllers\Api;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\BookingCreateRequest;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function booking(Request $request)
    {
        $request['user_id'] = (int) $request->user_id;
        $request['order_number'] = Booking::getOrderNumber();
        $request['order_status'] = 'processing';
        $request['payment_status'] = 'pending';

        $validator = Validator::make($request->all(), [
            'ground_id' => 'required|exists:grounds,id',
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required|after_or_equal:started_at',
            'total_price' => 'required|numeric',
            'order_status' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        // Simpan booking ke database
        $booking = Booking::create($validator->validated());

        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
        Config::$appendNotifUrl = route('webhook.midtrans');

        // Set item details for Midtrans
        $itemDetails = [
            [
                'id' => $booking->id,
                'price' => $booking->total_price,
                'quantity' => 1,
                'name' => 'Booking Lapangan' . $booking->ground->name,
            ],
        ];

        // Set customer details for Midtrans
        $customerDetails = [
            'first_name' => $booking->user->first_name,
            'last_name' => null,
            'email' => $booking->user->email,
            'phone' => $booking->user->phone,
        ];

        // Create Midtrans transaction
        $transaction = [
            'transaction_details' => [
                'order_id' => $booking->order_number,
                'gross_amount' => $booking->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        // Get Midtrans Snap Token
        $snapToken = Snap::getSnapToken($transaction);

        $booking->update([
            'ref_id' => $snapToken
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_number' => $booking->order_number,
        ]);
    }

    public function history()
    {
        $bookings = Booking::with('ground', 'ground.sportArena', 'user', 'promo')->where('user_id', auth()->id())->latest()->get();

        return response()->json([
            'data' => $bookings
        ]);
    }

    public function show($id)
    {
        $booking = Booking::findOrfail($id);

        return response()->json([
            'data' => $booking
        ]);
    }

    public function cancel($orderNumber)
    {
        $booking = Booking::where('user_id', auth()->id())->where('order_number', $orderNumber)->firstOrFail();

        if ($booking->order_status != 'pending' && $booking->order_status != 'processing') {
            return response()->json([
                'message' => 'This booking cannot be cannceled'
            ], 402);
        }

        return response()->json([
            'data' => $booking
        ]);
    }
}
