<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showVerificationForm(Request $request, $email = null)
    {
        $email = $email ?: $request->old('email');
        $user = User::where('email', $email)->first();
        $isRegistration = $user ? !$user->is_verified : false;

        return view('auth.verify-email', compact('email', 'isRegistration'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        $wasUnverified = !$user->is_verified;

        // Update user verification status
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'is_verified' => true,
            'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success',
                $wasUnverified ? 'Registration completed successfully!' : 'Login successful!'
            );
        }

        return redirect()->route('profile.show')->with('success',
            $wasUnverified ? 'Registration completed successfully!' : 'Login successful!'
        );
    }

    protected function generateNumericOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'User not found.']);
        }

        $otp = $this->generateNumericOTP();
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Send appropriate email based on verification status
        if (!$user->is_verified) {
            Mail::send('emails.registration-verify', ['otp' => $otp, 'name' => $user->name], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Registration Verification Code');
            });
        } else {
            Mail::send('emails.verify-email', ['otp' => $otp], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Login Verification Code');
            });
        }

        return back()
            ->withInput()
            ->with('success', 'New verification code has been sent to your email.');
    }
}
