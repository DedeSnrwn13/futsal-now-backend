<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SportArena;
use Illuminate\Http\Request;

class SportArenaController extends Controller
{
    public function random()
    {
        $sportArenas = SportArena::inRandomOrder()->take(5)->get();

        return response()->json(['data' => $sportArenas]);
    }

    public function search($query)
    {
        $sportArenas = SportArena::where('name', 'like', '%'.$query.'%')
            ->orWhere('city', 'like', '%'.$query.'%')->get();

        return response()->json(['data' => $sportArenas]);
    }
}
