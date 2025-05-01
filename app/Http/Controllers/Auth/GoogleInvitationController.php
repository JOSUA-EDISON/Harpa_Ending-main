<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInvitation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleInvitationController extends Controller
{
    /**
     * Show the invitation acceptance page
     */
    public function showAcceptInvitation($token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Undangan tidak valid atau sudah kadaluarsa');
        }

        return view('auth.invitation', compact('invitation'));
    }

    /**
     * Redirect to Google OAuth for accepting invitation
     */
    public function redirectToGoogle($token)
    {
        // Store the token in session to retrieve after OAuth callback
        session(['invitation_token' => $token]);

        // Set custom redirect URL for this invitation flow
        $redirectUrl = route('auth.google.invitation.callback');

        // Override the Google redirect config
        config(['services.google.redirect' => $redirectUrl]);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google OAuth callback for invitations
     */
    public function handleGoogleCallback()
    {
        try {
            // Get the token from session
            $token = session('invitation_token');
            if (!$token) {
                return redirect()->route('login')
                    ->with('error', 'Sesi undangan tidak ditemukan');
            }

            // Find the invitation
            $invitation = UserInvitation::where('token', $token)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$invitation) {
                return redirect()->route('login')
                    ->with('error', 'Undangan tidak valid atau sudah kadaluarsa');
            }

            // Set the same redirect URL for consistency
            config(['services.google.redirect' => route('auth.google.invitation.callback')]);

            // Get the user from Google
            $googleUser = Socialite::driver('google')->user();

            // Verify email matches the invitation
            if ($googleUser->email !== $invitation->email) {
                return redirect()->route('login')
                    ->with('error', 'Email Google yang digunakan tidak sesuai dengan undangan');
            }

            // Check if user already exists
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                    'password' => encrypt('google_auth_generated_password'),
                    'is_verified' => true,
                    'email_verified_at' => now(),
                    'role' => $invitation->role
                ]);
            } else {
                // Update existing user
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                    'role' => $invitation->role,
                    'is_verified' => true,
                    'email_verified_at' => now()
                ]);
            }

            // Mark invitation as used
            $invitation->is_used = true;
            $invitation->save();

            // Login the user
            Auth::login($user);

            // Tambahkan logika redirect berdasarkan role di bagian akhir handleGoogleCallback
            if ($user->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } else {
                return redirect()->intended('profile.show');
            }

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}
