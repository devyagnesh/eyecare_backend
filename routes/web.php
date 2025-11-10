<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApiDocumentationController;
use App\Http\Controllers\Admin\TestEmailController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Api\EyeExaminationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Password reset routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    Route::get('/password-reset-success', [PasswordResetController::class, 'showSuccess'])->name('password.reset-success');
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

Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])
    ->name('verification.resend');

// Public download route (signed URL for security)
Route::get('/download/eye-examination/{id}', [EyeExaminationController::class, 'publicDownload'])
    ->middleware(['signed'])
    ->name('eye-examination.public-download');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test email routes (for debugging)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/test-email', [TestEmailController::class, 'sendTestEmail'])->name('test-email');
        Route::post('/test-verification-email', [TestEmailController::class, 'sendTestVerificationEmail'])->name('test-verification-email');
    });
    
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
