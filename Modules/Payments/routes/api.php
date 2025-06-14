<?php

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentGatewaysController;



Route::prefix('payment')->group(function () {
    Route::get('/pay/{gateway}', [PaymentGatewaysController::class, 'pay']);
    Route::get('/callback/{gateway}', [PaymentGatewaysController::class, 'handleCallback']);
});
