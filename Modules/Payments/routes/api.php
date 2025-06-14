<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentsController;
use Modules\Payments\Http\Controllers\PaymentGatewaysController;



Route::prefix('payment')->middleware('auth:api')->group(function () {
    Route::get('/pay/{gateway}', [PaymentGatewaysController::class, 'pay']);
    Route::get('/callback/{gateway}', [PaymentGatewaysController::class, 'handleCallback']);
    Route::get('/all', [PaymentsController::class, 'index']);
});
