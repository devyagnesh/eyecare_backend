<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StoreController;
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
    Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify-api');
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
        Route::get('/check-email-verification', [AuthController::class, 'checkEmailVerification']);
        Route::post('/update-notification-token', [AuthController::class, 'updateNotificationToken']);
        Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend-api');
    });
    
    // Store routes (require email verification)
    Route::prefix('stores')->group(function () {
        Route::get('/', [StoreController::class, 'show'])->name('stores.show');
        Route::post('/', [StoreController::class, 'store'])->name('stores.create');
        Route::put('/', [StoreController::class, 'update'])->name('stores.update');
    });
    
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