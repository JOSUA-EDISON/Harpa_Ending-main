<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Custom reset implementation for our OTP-based system
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if token exists for this email
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'No reset request found for this email.']);
        }

        // Check token validity using Laravel's built-in hash check
        if (!Hash::check($request->token, $reset->token)) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Invalid password reset token.']);
        }

        // Check if token has expired (60 minutes)
        if ($reset->created_at < now()->subMinutes(60)) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Password reset token has expired.']);
        }

        // Find user and reset password
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'User not found.']);
        }

        // Reset the password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Delete the reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Fire password reset event
        event(new PasswordReset($user));

        // Instead of logging the user in automatically, redirect to login page
        // Auth::login($user);

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}
