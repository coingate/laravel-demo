<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
Route::get('/checkout/{orderId}', [HomeController::class, 'checkout'])->name('checkout');

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
});

Route::prefix('callback')->group(function () {
    Route::get('/{orderId}', [CallbackController::class, 'index'])->name('callback');
    Route::get('/success/{orderId}', [CallbackController::class, 'success'])->name('callback.success');
    Route::get('/failure/{orderId}', [CallbackController::class, 'failure'])->name('callback.failure');
});

