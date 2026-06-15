<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BloodInventoryController;
use App\Http\Controllers\Api\BloodRequestController;
use App\Http\Controllers\Api\DonationCenterController;
use App\Http\Controllers\Api\DonationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/centers', [DonationCenterController::class, 'index']);
Route::get('/centers/{donationCenter}', [DonationCenterController::class, 'show']);
Route::get('/blood-requests', [BloodRequestController::class, 'index']);
Route::get('/blood-requests/{bloodRequest}', [BloodRequestController::class, 'show']);
Route::get('/inventory', [BloodInventoryController::class, 'index']);
Route::get('/inventory/summary', [BloodInventoryController::class, 'summary']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/my-appointments', [AppointmentController::class, 'index']);
    Route::get('/my-appointments/{appointment}', [AppointmentController::class, 'show']);
    Route::put('/my-appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::delete('/my-appointments/{appointment}', [AppointmentController::class, 'destroy']);
    Route::get('/my-donations', [DonationController::class, 'index']);
    Route::get('/my-donations/{donation}', [DonationController::class, 'show']);
    Route::post('/blood-requests', [BloodRequestController::class, 'store']);
    Route::get('/my-blood-requests', [BloodRequestController::class, 'myRequests']);

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        Route::apiResource('/centers', DonationCenterController::class);
        Route::apiResource('/appointments', AppointmentController::class)->except(['store']);
        Route::get('/all-appointments', [AppointmentController::class, 'adminIndex']);
        Route::apiResource('/donations', DonationController::class);
        Route::apiResource('/inventory', BloodInventoryController::class)->except(['index']);
        Route::apiResource('/blood-requests', BloodRequestController::class)->except(['show', 'store']);
        Route::put('/blood-requests/{bloodRequest}/fulfill', [BloodRequestController::class, 'fulfill']);

        Route::get('/donors', [AdminController::class, 'donors']);
        Route::get('/donors/{user}', [AdminController::class, 'showDonor']);
        Route::put('/donors/{user}/toggle-eligibility', [AdminController::class, 'toggleEligibility']);
    });
});
