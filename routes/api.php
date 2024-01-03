<?php

use App\Http\Controllers\Api\GroundController;
use App\Http\Controllers\Api\GroundReviewController;
use App\Http\Controllers\Api\PaymentProviderController;
use App\Http\Controllers\Api\SportArenaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    // User
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']);

    // Sport Arena
    Route::prefix('sport-arenas')->name('sport_arenas.')->group(function () {
        Route::get('/random', [SportArenaController::class, 'random'])->name('random');
        Route::get('/search/{query}', [SportArenaController::class, 'search'])->name('search');
        Route::get('{sport_arena}/reviews', [GroundReviewController::class, 'bySportArena'])->name('reviews');

        // Ground
        Route::prefix('{sport_arena}/grounds')->name('grounds.')->group(function () {
            Route::get('/', [GroundController::class, 'index'])->name('index');
            Route::get('/search/{query}', [GroundController::class, 'search'])->name('search');

            // Review
            Route::prefix('{ground}/reviews')->name('reviews.')->group(function () {
                Route::get('/', [GroundReviewController::class, 'byGround'])->name('ground');
            });
        });
    });

    // Review
    Route::get('reviews/random', [GroundReviewController::class, 'random'])->name('reviews');

    // Payment Provider
    Route::get('payment-provider', [PaymentProviderController::class, 'index'])->name('payment-provider');
});
