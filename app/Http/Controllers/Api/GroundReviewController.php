<?php

namespace App\Http\Controllers\Api;

use App\Models\Ground;
use App\Models\SportArena;
use App\Models\GroundReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

    public function reviewByGround(SportArena $sportArena, Ground $ground, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ground_id' => 'required|exists:grounds,id',
            'user_id' => 'required|exists:users,id',
            'rate' => 'required',
            'comment' => 'nullable|string'
        ]);

        $review = GroundReview::create($validator->validated());

        return response()->json(['data' => $review]);
    }
}
