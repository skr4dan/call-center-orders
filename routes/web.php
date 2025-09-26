<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrdersController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'create'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', function (Request $request) {
        return match ($request->user()->role->name) {
            Role::MANAGER => redirect(route('orders.index')),
            Role::OPERATOR => redirect(route('orders.create')),
            default => redirect(404),
        };
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    });

    Route::middleware('role:operator')->group(function () {
        Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    });
});
