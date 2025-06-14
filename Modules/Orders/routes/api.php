<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Http\Controllers\OrdersController;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('orders', OrdersController::class)->middlewareFor('destroy', 'payment.check');
    Route::post('orders/{order}', [OrdersController::class, 'update'])->middleware('payment.check');
    Route::post('/orders/{order}/confirm', [OrdersController::class, 'confirm']);
});
