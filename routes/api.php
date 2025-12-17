<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VolumeHistoryController;

// Public routes
Route::post('/employee/login', [EmployeeController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Employee routes
    Route::get('/employee/profile', [EmployeeController::class, 'profile']);
    Route::post('/employee/logout', [EmployeeController::class, 'logout']);

    // Volume Histories

    Route::get('/volume-histories',[\App\Http\Controllers\Api\VolumeHistoryController::class,'index']);
    Route::get('volume-histories/export', [VolumeHistoryController::class, 'export']);

    Route::post('/input-volume', [\App\Http\Controllers\Api\VolumeHistoryController::class, 'store']);
    Route::post('/update-volume/{id}', [\App\Http\Controllers\Api\VolumeHistoryController::class, 'update']);
    Route::post('/delete-volume/{id}', [\App\Http\Controllers\Api\VolumeHistoryController::class, 'delete']);
    

    // Customer routes
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/search/{code}', [CustomerController::class, 'searchByCode']);
});