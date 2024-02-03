<?php

namespace App\Http\Controllers\Api;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingCreateRequest;

class BookingController extends Controller
{
    public function booking(BookingCreateRequest $request)
    {
        $validatedData = $request->validated();

        try {
            // Simpan booking ke database
            $booking = Booking::create($validatedData);

            // Generate order number if not provided
            if (!$booking->order_number) {
                $booking->order_number = Booking::getOrderNumber();
                $booking->save();
            }

            // Set Midtrans configuration
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = config('services.midtrans.is_sanitized');
            Config::$is3ds = config('services.midtrans.is_3ds');
            Config::$appendNotifUrl = route('api.webhook.midtrans');

            // Set item details for Midtrans
            $itemDetails = [
                [
                    'id' => $booking->id,
                    'price' => $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Booking',
                ],
            ];

            // Set customer details for Midtrans
            $customerDetails = [
                'first_name' => $booking->user->first_name,
                'last_name' => $booking->user->last_name,
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

            return response()->json([
                'snap_token' => $snapToken,
                'order_number' => $booking->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {

    }

    public function show($id)
    {

    }

    public function cancel($id)
    {

    }
}
