<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmailController extends Controller
{
    /**
     * Send test email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        try {
            Mail::raw('This is a test email from Eyecare Management System. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Eyecare Management System');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $email,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    /**
     * Send test verification email
     */
    public function sendTestVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        try {
            $verificationUrl = url('/email/verify/test/' . md5($request->email));
            
            Mail::send('emails.verify-email', [
                'name' => $request->name,
                'verificationUrl' => $verificationUrl,
            ], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Verification Email - Eyecare Management System');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test verification email sent successfully to ' . $request->email,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Test verification email failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test verification email: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }
}
