<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TimesheetController;

Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User routes
    Route::post('/user', [UserController::class, 'create']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);

    // Project routes
    Route::post('/project', [ProjectController::class, 'create']);
    Route::get('/project/{id}', [ProjectController::class, 'show']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::put('/project/{id}', [ProjectController::class, 'update']);
    Route::delete('/project/{id}', [ProjectController::class, 'delete']);

    // Timesheet routes
    Route::post('/timesheet', [TimesheetController::class, 'create']);
    Route::get('/timesheet/{id}', [TimesheetController::class, 'show']);
    Route::get('/timesheets', [TimesheetController::class, 'index']);
    Route::put('/timesheet/{id}', [TimesheetController::class, 'update']);
    Route::delete('/timesheet/{id}', [TimesheetController::class, 'delete']);
});