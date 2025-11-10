<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset success page.
     */
    public function showSuccess()
    {
        return view('auth.password-reset-success');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLink(Request $request, PasswordResetService $passwordResetService)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
        ]);

        try {
            $result = $passwordResetService->sendResetLink($request->email);
            
            return redirect()->back()
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            
            if ($statusCode === 429) {
                return redirect()->back()
                    ->with('error', $e->getMessage())
                    ->withInput();
            }
            
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the reset password form.
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid reset link.');
        }

        // Verify token is valid
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid or expired reset token.');
        }

        // Check if token is expired (60 minutes)
        $tokenAge = now()->diffInMinutes($resetRecord->created_at);
        if ($tokenAge > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->with('error', 'Reset token has expired. Please request a new password reset link.');
        }

        // Verify token
        if (!Hash::check($token, $resetRecord->token)) {
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid reset token.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function reset(Request $request, PasswordResetService $passwordResetService)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'token.required' => 'Reset token is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        try {
            $result = $passwordResetService->resetPassword(
                $request->email,
                $request->token,
                $request->password
            );
            
            return redirect()->route('password.reset-success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}

