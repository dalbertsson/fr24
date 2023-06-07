<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fr24\FlightsController;
use App\Http\Controllers\Fr24\TicketsController;
use App\Http\Controllers\Fr24\Flights\FlightTicketsController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('/flights', FlightsController::class)->except(['create', 'edit']);

    Route::resource('/flights.tickets', FlightTicketsController::class)->except(['create', 'edit']);

    Route::resource('/tickets', TicketsController::class)->except(['create', 'edit', 'store']);

});