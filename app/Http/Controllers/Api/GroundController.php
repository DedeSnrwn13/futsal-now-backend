<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SportArena;
use Illuminate\Http\Request;

class GroundController extends Controller
{
    public function index(SportArena $sportArena)
    {
        $grounds = $sportArena->grounds;

        return response()->json(['data' => $grounds]);
    }

    public function search(SportArena $sportArena, $query)
    {
        $grounds = $sportArena->grounds()->where('is_available', true)
            ->where('name', 'like', '%'.$query.'%')
            ->orWhere('rental_price', 'like', '%'.$query.'%')
            ->get();

        return response()->json(['data' => $grounds]);
    }
}
