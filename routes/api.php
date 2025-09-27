<?php

use App\Http\Controllers\Api\OrdersController;
use Illuminate\Support\Facades\Route;

// Public API routes - no authentication required
Route::get('/orders', [OrdersController::class, 'index']);
Route::post('/orders', [OrdersController::class, 'store']);
Route::get('/orders/stats', [OrdersController::class, 'stats']);
