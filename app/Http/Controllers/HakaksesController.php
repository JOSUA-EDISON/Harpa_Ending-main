<?php

namespace App\Http\Controllers;

use App\Models\hakakses;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HakaksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        if ($search) {
            $data['hakakses'] = hakakses::where('role', 'admin')
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                })
                ->get();
        } else {
            $data['hakakses'] = hakakses::where('role', 'admin')->get();
        }
        return view('layouts.hakakses.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('layouts.hakakses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required'
        ]);

        $hakakses = new hakakses();
        $hakakses->name = $request->name;
        $hakakses->email = $request->email;
        $hakakses->password = bcrypt($request->password);
        $hakakses->role = $request->role;
        $hakakses->save();

        return redirect()->route('admin.hakakses.index')
            ->with('message', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(hakakses $hakakses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $hakakses = hakakses::find($id);
        return view('layouts.hakakses.edit', compact('hakakses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $hakakses = hakakses::find($id);
        $hakakses->role = $request->role;
        $hakakses->save();
        return redirect()->route('admin.hakakses.index')->with('message', 'Role berhasil diupdate!');
    }

    public function sendOtp($id)
    {
        $hakakses = hakakses::find($id);
        $otp = mt_rand(100000, 999999);

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $id, $otp, now()->addMinutes(5));

        // Send OTP via email
        Mail::send('emails.otp', ['otp' => $otp], function($message) use ($hakakses) {
            $message->to($hakakses->email)
                    ->subject('OTP untuk Melihat Password');
        });

        return redirect()->back()->with('otp_sent', true);
    }

    public function verifyOtp(Request $request, $id)
    {
        $hakakses = hakakses::find($id);
        $storedOtp = Cache::get('otp_' . $id);

        if (!$storedOtp || $request->otp != $storedOtp) {
            return redirect()->back()->with('error', 'OTP tidak valid atau sudah kadaluarsa');
        }

        // Clear the OTP from cache
        Cache::forget('otp_' . $id);

        // Generate new random password
        $newPassword = Str::random(12); // 12 karakter random

        // Save new password
        $hakakses->password = Hash::make($newPassword);
        $hakakses->save();

        // Force logout from all sessions
        DB::table('sessions')->where('user_id', $hakakses->id)->delete();

        // Send new password via email
        Mail::send('emails.password-reset', [
            'user' => $hakakses,
            'password' => $newPassword
        ], function($message) use ($hakakses) {
            $message->to($hakakses->email)
                    ->subject('Reset Password Berhasil');
        });

        return redirect()->back()->with('success', 'Password telah direset dan dikirim ke email ' . $hakakses->email . '. User akan diminta login ulang.');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $hakakses = hakakses::find($id);
        $hakakses->password = Hash::make($request->new_password);
        $hakakses->save();

        // Force logout from all sessions
        DB::table('sessions')->where('user_id', $hakakses->id)->delete();

        return redirect()->back()->with('success', 'Password berhasil diupdate! User akan diminta login ulang.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $hakakses = hakakses::find($id);
        if ($hakakses) {
            $hakakses->delete();
            return redirect()->route('admin.hakakses.index')
                ->with('message', 'User berhasil dihapus!');
        }

        return redirect()->route('admin.hakakses.index')
            ->with('error', 'User tidak ditemukan!');
    }

    /**
     * Invite a user via Google Auth
     */
    public function inviteGoogle(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,user'
        ]);

        // Check if user already exists
        $existingUser = hakakses::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'User dengan email tersebut sudah terdaftar');
        }

        // Check if there's already an active invitation
        $existingInvite = UserInvitation::where('email', $request->email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvite) {
            // Extend the expiration
            $existingInvite->expires_at = Carbon::now()->addDays(3);
            $existingInvite->save();

            // Re-send the invitation
            $this->sendInvitationEmail($existingInvite);

            return redirect()->route('admin.hakakses.index')
                ->with('message', 'Undangan sudah terkirim sebelumnya. Masa berlaku undangan telah diperpanjang.');
        }

        // Create new invitation
        $invitation = new UserInvitation();
        $invitation->email = $request->email;
        $invitation->token = Str::random(64);
        $invitation->role = $request->role;
        $invitation->expires_at = Carbon::now()->addDays(3);
        $invitation->invited_by = null; // No inviter tracking for now
        $invitation->save();

        // Send invitation email
        $this->sendInvitationEmail($invitation);

        return redirect()->route('admin.hakakses.index')
            ->with('message', 'Undangan berhasil dikirim ke ' . $request->email);
    }

    /**
     * Send invitation email
     */
    private function sendInvitationEmail(UserInvitation $invitation)
    {
        $inviteUrl = route('auth.google.accept', ['token' => $invitation->token]);

        Mail::send('emails.invite', [
            'invitation' => $invitation,
            'inviteUrl' => $inviteUrl
        ], function($message) use ($invitation) {
            $message->to($invitation->email)
                    ->subject('Undangan untuk Bergabung dengan Harpa');
        });
    }
}
