<?php

namespace App\Http\Controllers\Api;

use App\Models\Ground;
use App\Models\SportArena;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index(SportArena $sportArena)
    {
        $grounds =  Ground::with(['sportArena', 'groundReviews' => function ($query) {
            $query->selectRaw('AVG(rate) as average_rate, ground_id')
                ->groupBy('ground_id');
        }])
        ->where('sport_arena_id', $sportArena->id)
        ->where('is_available', true)
        ->get();

        $result = $grounds->map(function ($ground) {
            $groundWithImage = $ground;

            $image = null;
            if (is_array($groundWithImage->image) && count($groundWithImage->image) > 0) {
                $image = $groundWithImage->image[0];
            }
            $rate = $groundWithImage->groundReviews()->avg('rate');
            $price = $groundWithImage->avg('rental_price');

            $groundData = $ground->toArray();
            $groundData['rate'] = $rate;
            $groundData['price'] = $price;
            $groundData['image'] = $image;
            $groundData['image_thumbnail'] = $image;

            return $groundData;
        });

        return response()->json(['data' => $result]);
    }

    public function show(SportArena $sportArena, Ground $ground)
    {
        $ground = $ground->with('sportArena')->firstOrFail();
        $rate = $ground->groundReviews()->avg('rate');

        $groundData = $ground->toArray();
        $groundData['rate'] = $rate;

        return response()->json([
            'data' => $groundData
        ]);
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
