<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ground;
use App\Models\GroundReview;
use App\Models\SportArena;
use Illuminate\Http\Request;

class GroundReviewController extends Controller
{
    public function random()
    {
        $reviews = GroundReview::with('ground', 'ground.sportArena', 'user')
            ->inRandomOrder()->take(50)
            ->orderBy('rate', 'desc')->get();

        return response()->json(['data' => $reviews]);
    }

    public function bySportArena(SportArena $sportArena)
    {
        $reviews = GroundReview::with('ground', 'user')
            ->whereHas('ground', function ($q) use ($sportArena) {
                $q->where('sport_arena_id', $sportArena->id);
            })
            ->orderBy('rate', 'desc')->take(50)->get();

        return response()->json(['data' => $reviews]);
    }

    public function byGround(SportArena $sportArena, Ground $ground)
    {
        $reviews = GroundReview::with('ground', 'user')
            ->where('ground_id', $ground->id)
            ->orderBy('rate', 'desc')->take(50)->get();

        return response()->json(['data' => $reviews]);
    }
}
