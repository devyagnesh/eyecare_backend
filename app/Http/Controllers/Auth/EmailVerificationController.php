<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

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
}
