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
    Route::post('/forgot-password', [UserController::class, 'forgetPassword']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('user', [UserController::class, 'getUser']);
        Route::post('logout', [UserController::class, 'logout']);

    });

});

Route::prefix('tasks')->group(function () {

    Route::get('/', [TaskController::class, 'getAllTasks']);
    Route::post('/', [TaskController::class, 'createTask']);
    Route::get('/{id}/project', [TaskController::class, 'getProjectRelatedToTask']);

});

// Projects Routes
Route::prefix('projects')->group(function () {

    Route::middleware(['auth:sanctum', 'admin'])
        ->get('/', [ProjectController::class, 'getAllProjects']);

    Route::middleware('auth:sanctum')
        ->post('/', [ProjectController::class, 'createProject']);

    Route::get('/{id}/tasks', [ProjectController::class, 'getTasksRelatedToProject']);

    Route::get('{id}', [ProjectController::class, 'getProject']); // find project with its created_by id and name

});

// Route::get('/send', function () {
//     Mail::to('om8622145@gmail.com')->send(new ForgetPassMail());
//     return response('email is send .....');
// });
