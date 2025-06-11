<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
      Route::post('/students', [StudentController::class, 'store']);
       Route::get('/students', [StudentController::class, 'index']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
           Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Add other protected routes here
});