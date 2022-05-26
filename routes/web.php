<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('settings')->as('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/', [SettingsController::class, 'update'])->name('update');
});

Route::prefix('orders')->as('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::post('/{uuid}/callback', CallbackController::class)->name('callback');
    Route::get('/{uuid}/success', [OrderController::class, 'success'])->name('success');
    Route::get('/{uuid}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::put('/{uuid}/fetch-status', [OrderController::class, 'fetchCoingateStatus'])->name('fetch-status');
    Route::get('/{uuid}/callback-history', [OrderController::class, 'callbackHistory'])->name('callback-history');
    Route::get('/{uuid}/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/{uuid}/check-status', [OrderController::class, 'checkStatus'])->name('check-status');
});
