<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user or create a new one
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Check if user with this email exists
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Update existing user with Google credentials
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                    ]);

                    $user = $existingUser;
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                        'password' => encrypt('google_auth_generated_password'), // Set a default encrypted password
                        'is_verified' => true, // Users from Google are already verified
                        'email_verified_at' => now(),
                        'role' => 'user' // Default role
                    ]);
                }
            } else {
                // Update token information
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                ]);
            }

            // Login the user
            Auth::login($user);

            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('profile.show');

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}
