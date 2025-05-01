<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset OTP email to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetOtpEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => trans('passwords.user')]);
        }

        // Generate OTP
        $otp = $this->generateNumericOTP();

        // Store in password_resets table (reusing Laravel's reset token table)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Send OTP email
        Mail::send('emails.reset-password-otp', [
            'otp' => $otp,
            'name' => $user->name
        ], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Password Reset OTP');
        });

        return redirect()->route('password.otp', ['email' => $user->email])
            ->with('status', trans('passwords.sent'));
    }

    /**
     * Display the password reset OTP verification view for the given token.
     *
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetOtpForm(Request $request)
    {
        return view('auth.passwords.reset-otp')->with(
            ['email' => $request->email]
        );
    }

    /**
     * Verify the OTP and redirect to password reset form
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'email' => 'required|email'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->where('created_at', '>', Carbon::now()->subMinutes(60))
            ->first();

        if (!$reset) {
            return back()
                ->withInput()
                ->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Generate a new token for the password reset form
        $token = Str::random(60);
        $hashedToken = bcrypt($token); // Hash the token for storage

        // Update the token in database
        DB::table('password_resets')->where('email', $request->email)->update([
            'token' => $hashedToken,
            'created_at' => Carbon::now() // Reset the timer for token expiration
        ]);

        return redirect()->route('password.reset', [
            'token' => $token, // Send unhashed token to the form
            'email' => $request->email
        ]);
    }

    /**
     * Generate a numeric OTP
     *
     * @return string
     */
    protected function generateNumericOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Resend OTP
     */
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

        // Generate OTP
        $otp = $this->generateNumericOTP();

        // Update in password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Send OTP email
        Mail::send('emails.reset-password-otp', [
            'otp' => $otp,
            'name' => $user->name
        ], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Password Reset OTP');
        });

        return back()
            ->withInput()
            ->with('status', 'New verification code has been sent to your email.');
    }
}
