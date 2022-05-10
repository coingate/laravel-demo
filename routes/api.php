<?php

use App\Http\Controllers\API\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::post('pay-with-coingate', [CheckoutController::class, 'payWithCoinGate']);
Route::get('orders/{orderId}', [CheckoutController::class, 'getOrder']);
