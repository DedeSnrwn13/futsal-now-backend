<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    public function index()
    {
        if (
            auth()->id() === 1 && auth()->user()->email_verified_at &&
            auth()->user()->email === 'admin@futsal.com'
        ) {
            $provider = PaymentProvider::first();

            return response()->json(['data' => $provider]);
        } else {
            return response()->json([], 401);
        }
    }
}
