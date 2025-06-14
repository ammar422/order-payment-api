<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware('guest:api')->group(function () {
    Route::post('register', [UsersController::class, 'register']);
    Route::post('login', [UsersController::class, 'login']);
});
Route::middleware('auth:api')->group(function () {
    Route::post('logout',  [UsersController::class, 'logout']);
    Route::post('refresh', [UsersController::class, 'refresh']);
    Route::get('me', [UsersController::class, 'me']);
});
