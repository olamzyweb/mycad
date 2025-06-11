<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\ClassroomController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    //protected students routes
      Route::post('/students', [StudentController::class, 'store']);
       Route::get('/students', [StudentController::class, 'index']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
           Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    //protected classrooms routes
       Route::get('/classrooms', [ClassroomController::class, 'index']);
       Route::post('/classrooms', [ClassroomController::class, 'store']);
       Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);
    Route::put('/classrooms/{id}', [ClassroomController::class, 'update']);
    Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Add other protected routes here
});