<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect(route('verification.failed'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(route('verification.already-verified'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(route('verification.success'));
    }

    /**
     * Show the email verification success page.
     */
    public function showSuccess()
    {
        return view('auth.verify-email-success');
    }

    /**
     * Show the email verification failed page.
     */
    public function showFailed()
    {
        return view('auth.verify-email-failed');
    }

    /**
     * Show the already verified page.
     */
    public function showAlreadyVerified()
    {
        return view('auth.verify-email-already');
    }

    /**
     * Resend email verification notification (web route).
     */
    public function resend(Request $request, EmailVerificationService $emailVerificationService)
    {
        // If user is authenticated, use authenticated user
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            // If not authenticated, redirect to login with message
            return redirect()->route('login')
                ->with('error', 'Please log in to resend verification email.');
        }

        try {
            $result = $emailVerificationService->resendVerificationEmail($user);
            
            return redirect()->back()
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            
            if ($statusCode === 400) {
                // Email already verified
                return redirect()->route('verification.already-verified');
            } elseif ($statusCode === 429) {
                // Rate limit exceeded
                return redirect()->back()
                    ->with('error', $e->getMessage());
            } else {
                // Other errors
                return redirect()->back()
                    ->with('error', $e->getMessage());
            }
        }
    }
}
