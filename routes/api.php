<?php

use App\Http\Controllers\Api\OrdersController;
use Illuminate\Support\Facades\Route;

// Public routes (if needed)
// Route::get('/orders/public', [OrdersController::class, 'publicIndex']);

// Manager can view orders and stats
Route::middleware('role:manager')->group(function () {
    Route::get('/orders', [OrdersController::class, 'index']);
    Route::get('/orders/stats', [OrdersController::class, 'stats']);
});

// Operator can create orders
Route::middleware('role:operator')->group(function () {
    Route::post('/orders', [OrdersController::class, 'store']);
});
