<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Api\Admin\EventController as AdminEventController;
use App\Http\Controllers\Api\EventController as UserEventController;
use App\Http\Controllers\Api\TicketController as UserTicketController;

// ... (Public Auth Routes) ...
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Authenticated User Routes (auth:api middleware) ---
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Ticket Booking Route
    Route::post('/events/{event}/tickets', [UserTicketController::class, 'store'])
          ->middleware('throttle:3,1')
          ->name('tickets.store');

    // --- View My Booked Tickets Route --- // <-- ADD THIS ROUTE
    Route::get('/my-tickets', [UserTicketController::class, 'index'])->name('tickets.index');
});

// ... (Admin Routes) ...
Route::middleware(['auth:api', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::apiResource('venues', AdminVenueController::class);
    Route::apiResource('events', AdminEventController::class);
});

// ... (Public Event Routes) ...
Route::get('/events', [UserEventController::class, 'index']);
Route::get('/events/search', [UserEventController::class, 'search']);
Route::get('/events/{event}', [UserEventController::class, 'show']);