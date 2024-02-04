<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ground;
use App\Models\SportArena;
use Illuminate\Http\Request;

class SportArenaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function recommendation()
    {
        $sportArenas = Ground::with(['sportArena', 'groundReviews'])
            ->whereHas('groundReviews')
            ->get()
            ->sortByDesc(function ($ground) {
                return $ground->groundReviews->sum('rate');
            })
            ->pluck('sportArena')
            ->unique()
            ->take(5);

        $result = $sportArenas->map(function ($sportArena) {
            $groundWithImage = Ground::with('sportArena')
                ->where('sport_arena_id', $sportArena->id)
                ->whereHas('groundReviews')
                ->first();

            $image = null;
            if (is_array($groundWithImage->image) && count($groundWithImage->image) > 0) {
                $image = $groundWithImage->image[0];
            }
            $rate = $groundWithImage->groundReviews()->avg('rate');
            $price = $groundWithImage->avg('rental_price');

            $sportArenaData = $sportArena->toArray();
            $sportArenaData['image'] = $image;
            $sportArenaData['price'] = $price;
            $sportArenaData['rate'] = $rate;

            return $sportArenaData;
        });

        return response()->json(['data' => $result]);
    }

    public function search($query)
    {
        $sportArenas = SportArena::where('name', 'like', '%'.$query.'%')
            ->orWhere('city', 'like', '%'.$query.'%')->get();

        $result = $sportArenas->map(function ($sportArena) {
            $groundWithImage = Ground::with('sportArena')
                ->where('sport_arena_id', $sportArena->id)
                ->first();

            $image = null;
            if (is_array($groundWithImage->image) && count($groundWithImage->image) > 0) {
                $image = $groundWithImage->image[0];
            }
            $rate = $groundWithImage->groundReviews()->avg('rate');
            $price = $groundWithImage->avg('rental_price');

            $sportArenaData = $sportArena->toArray();
            $sportArenaData['image'] = $image;
            $sportArenaData['price'] = $price;
            $sportArenaData['rate'] = $rate;

            return $sportArenaData;
        });

        return response()->json(['data' => $result], 200);
    }
}
