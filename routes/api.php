<?php

use App\Http\Controllers\Api\V1\Client\Booking\BookingController;
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

Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {

    Route::post('/order', [BookingController::class, 'order'])
        ->name('order');

    Route::patch('{booking}', [BookingController::class, 'updatePartially'])
        ->name('update-partially');

    Route::patch('{booking}/update-reservation', [BookingController::class, 'updateReservation'])
        ->name('update-reservation');

    Route::delete('{booking}', [BookingController::class, 'delete'])
        ->name('delete');

    Route::post('{booking}/approve', [BookingController::class, 'approve'])
        ->name('approve');
});
