<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::prefix('auth')->group(function () {

    // Public auth routes
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('user', [UserController::class, 'getUser']);
        Route::post('logout', [UserController::class, 'logout']);

    });

});

Route::prefix('tasks')->group(function () {

    Route::get('/', [TaskController::class, 'getAllTasks']);
    Route::post('/', [TaskController::class, 'createTask']);

});

Route::prefix('projects')->group(function () {

    Route::get('/', [ProjectController::class, 'getAllProjects']);
    Route::post('/', [ProjectController::class, 'createProject']);

});