<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TimesheetController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User routes
    Route::post('/user', [UserController::class, 'create']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/delete', [UserController::class, 'delete']);

    // Project routes
    Route::post('/project', [ProjectController::class, 'create']);
    Route::get('/project/{id}', [ProjectController::class, 'show']);
    Route::get('/project', [ProjectController::class, 'index']);
    Route::post('/project/update', [ProjectController::class, 'update']);
    Route::post('/project/delete', [ProjectController::class, 'delete']);

    // Timesheet routes
    Route::post('/timesheet', [TimesheetController::class, 'create']);
    Route::get('/timesheet/{id}', [TimesheetController::class, 'show']);
    Route::get('/timesheet', [TimesheetController::class, 'index']);
    Route::post('/timesheet/update', [TimesheetController::class, 'update']);
    Route::post('/timesheet/delete', [TimesheetController::class, 'delete']);
});
