<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApiDocumentationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Email verification routes (public)
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/email/verify-success', [EmailVerificationController::class, 'showSuccess'])
    ->name('verification.success');

Route::get('/email/verify-failed', [EmailVerificationController::class, 'showFailed'])
    ->name('verification.failed');

Route::get('/email/verify-already', [EmailVerificationController::class, 'showAlreadyVerified'])
    ->name('verification.already-verified');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin routes - Protected by authentication
    Route::prefix('admin')->name('admin.')->group(function () {
        // Roles management
        Route::resource('roles', RoleController::class);
        
        // Permissions management
        Route::resource('permissions', PermissionController::class);
        
        // Users management
        Route::resource('users', UserController::class);
        
        // API Documentation
        Route::get('api-documentation', [ApiDocumentationController::class, 'index'])->name('api-documentation.index');
        Route::get('api-documentation/download', [ApiDocumentationController::class, 'downloadPostmanCollection'])->name('api-documentation.download');
    });
});
