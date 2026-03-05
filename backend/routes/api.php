<?php

use App\Http\Controllers\BloodDonationController;
use App\Http\Controllers\BloodRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/sanctum/token', [App\Http\Controllers\AuthController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/sanctum/token', [App\Http\Controllers\AuthController::class, 'revokeToken']);
    
    Route::get('/user', [App\Http\Controllers\AuthController::class, 'user']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    
    Route::apiResource('donations', BloodDonationController::class);
    Route::apiResource('requests', BloodRequestController::class);
    Route::get('/available-blood', [BloodRequestController::class, 'availableBlood']);
});

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
