<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroundController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\SportArenaController;
use App\Http\Controllers\Api\GroundReviewController;
use App\Http\Controllers\Api\PaymentProviderController;
use App\Http\Controllers\Api\PromoController;

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
], function () {
    // Guest
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/refresh', [UserController::class, 'refresh']);

    // User
    Route::get('/user-profile', [UserController::class, 'userProfile']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Sport Arena
    Route::prefix('sport-arenas')->name('sport_arenas.')->group(function () {
        Route::get('/recommendation/limit', [SportArenaController::class, 'recommendation'])->name('recommendation');
        Route::get('/search/{query}', [SportArenaController::class, 'search'])->name('search');
        Route::get('/{sport_arena}/reviews', [GroundReviewController::class, 'bySportArena'])->name('reviews');

        // Ground
        Route::prefix('{sport_arena}/grounds')->name('grounds.')->group(function () {
            Route::get('/', [GroundController::class, 'index'])->name('index');
            Route::get('/{id}', [GroundController::class, 'show'])->name('show');
            Route::get('/search/{query}', [GroundController::class, 'search'])->name('search');

            // Booking Ground
            Route::post('/{ground}/book', [BookingController::class, 'booking'])->name('store');

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

    // Booking
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/history', [BookingController::class, 'history'])->name('history');
        Route::get('/{id}/show', [BookingController::class, 'show'])->name('show');
        Route::put('/{order_number}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });

    // Feedback
    Route::post('/feedback', [UserController::class, 'feedback'])->name('feedback');

    // Promo
    Route::prefix('promos')->name('promos.')->group(function () {
        Route::get('/limit', [PromoController::class, 'limit'])->name('limit');
        Route::get('/all', [PromoController::class, 'all'])->name('all');
    });
});

Route::post('/webhook/midtrans', [WebhookController::class, 'midtransCallback'])->name('webhook.midtrans');