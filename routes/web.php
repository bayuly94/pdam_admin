<?php


use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VolumeHistoryController;
use App\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// Route::get('/', function () {
//     return view('welcome');
// });

// Home Route - Redirect to admin
Route::get('/', function () {
    return redirect('/admin');
})->name('home');

// Clear application cache via URL (secure with secret key)
Route::get('/optimize', function() {
    Artisan::call('optimize:clear');
    return redirect()->route('home');
})->name('optimize');

// migrate
Route::get('/migrate', function() {
    Artisan::call('migrate');
    return redirect()->route('home');
})->name('migrate');

Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');


// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

    // Employee Management Routes
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)->except(['show']);
    Route::get('employees/{employee}', [\App\Http\Controllers\Admin\EmployeeController::class, 'show'])->name('employees.show');


    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->except(['show'])->names('customers');
    
    // Volume Histories
    Route::resource('volume-histories', \App\Http\Controllers\Admin\VolumeHistoryController::class)->names('volume-histories');
    Route::get('volume-histories-export', [VolumeHistoryController::class, 'export'])->name('volume-histories.export');

    // Settings
    Route::get('/settings', [SettingController::class, 'form'])->name('settings.form');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});




require __DIR__.'/auth.php';
