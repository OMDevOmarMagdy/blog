<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::prefix('auth')->group(function () {

    // Public auth routes
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [UserController::class, 'getUser']);
        Route::post('logout', [UserController::class, 'logout']);

    });

});