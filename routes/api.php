<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EyeExaminationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TermsAndConditionController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DataExportController;
use App\Http\Controllers\Admin\TestEmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-email', [AuthController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('verification.verify-api');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot-api');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset-api');
});

// Public terms and conditions routes
Route::prefix('terms')->group(function () {
    Route::get('/latest', [TermsAndConditionController::class, 'getLatest'])->name('terms.latest');
});

// Test email routes (for debugging)
Route::prefix('test')->group(function () {
    Route::post('/send-email', [TestEmailController::class, 'sendTestEmail']);
    Route::post('/send-verification-email', [TestEmailController::class, 'sendTestVerificationEmail']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/check-email-verification', [AuthController::class, 'checkEmailVerification'])
            ->middleware('throttle:email-verification-check');
        Route::post('/update-notification-token', [AuthController::class, 'updateNotificationToken']);
        Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend-api');
        Route::delete('/account', [AuthController::class, 'deleteAccount'])->name('auth.delete-account');
    });
    
    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/today', [NotificationController::class, 'getTodayNotifications'])->name('notifications.today');
    });
    
    // Data export routes
    Route::prefix('export')->group(function () {
        Route::get('/all', [DataExportController::class, 'exportAll'])->name('export.all');
    });
    
    // Store routes (require email verification)
    Route::prefix('stores')->group(function () {
        Route::get('/check', [StoreController::class, 'check'])->name('stores.check');
        Route::get('/', [StoreController::class, 'show'])->name('stores.show');
        Route::post('/', [StoreController::class, 'store'])->name('stores.create');
        Route::put('/', [StoreController::class, 'update'])->name('stores.update');
    });
    
    // Statistics routes (require store)
    Route::prefix('statistics')->group(function () {
        Route::get('/', [StatisticsController::class, 'index'])->name('statistics.index');
    });
    
    // Customer routes (require store)
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.create');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
    
    // Eye Examination routes (require store)
    Route::prefix('eye-examinations')->group(function () {
        Route::get('/', [EyeExaminationController::class, 'index'])->name('eye_examinations.index');
        Route::post('/', [EyeExaminationController::class, 'store'])->name('eye_examinations.create');
        Route::get('/customer/{customerId}/previous-prescription', [EyeExaminationController::class, 'getPreviousPrescriptionDate'])->name('eye_examinations.previous-prescription');
        Route::get('/{id}/download-pdf', [EyeExaminationController::class, 'downloadPdf'])->name('eye_examinations.download-pdf');
        Route::get('/{id}', [EyeExaminationController::class, 'show'])->name('eye_examinations.show');
        Route::put('/{id}', [EyeExaminationController::class, 'update'])->name('eye_examinations.update');
        Route::delete('/{id}', [EyeExaminationController::class, 'destroy'])->name('eye_examinations.destroy');
    });
    
    // Order routes (require store)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('orders.create');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('/{id}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    });
    
    // Settings routes (admin only)
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/', [SettingController::class, 'store'])->name('settings.create');
        Route::get('/group/{group}', [SettingController::class, 'getByGroup'])->name('settings.by-group');
        Route::get('/{setting}', [SettingController::class, 'show'])->name('settings.show');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
    });
    
    // Terms and conditions routes (for viewing terms only - acceptance is required during registration)
    // Note: Terms acceptance is mandatory during signup, so no separate accept endpoint is needed
    
    // Example: Get authenticated user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()->load('role.permissions'),
            ],
        ]);
    });
});