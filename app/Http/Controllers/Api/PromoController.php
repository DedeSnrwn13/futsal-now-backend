<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function limit()
    {
        $promos = Promo::where('status', true)
            ->whereDate('started_at', '<=', now())
            ->whereDate('ended_at', '>=', now())
            ->latest()->limit(3)->get();

        if (count($promos) > 0) {
            return response()->json([
                'data' => $promos
            ], 200);
        } else {
            return response()->json([
                'data' => $promos,
                'message' => 'Not Found'
            ], 404);
        }
    }

    public function all()
    {
        $promos = Promo::where('status', true)
            ->whereDate('started_at', '<=', now())
            ->whereDate('ended_at', '>=', now())
            ->latest()->get();

        if (count($promos) > 0) {
            return response()->json([
                'data' => $promos
            ], 200);
        } else {
            return response()->json([
                'data' => $promos,
                'message' => 'Not Found'
            ], 404);
        }
    }
}
