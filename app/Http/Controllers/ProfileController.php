<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        \App\Models\User::where('id', $user->id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('profile.edit')->with('status', 'Profil Berhasil Di Update!');
    }

    public function changepassword()
    {
        return view('profile.changepassword', ['user' => Auth::user()]);
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password Sebelumnya Salah!']);
        }

        \App\Models\User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', 'Password berhasil Diubah!');
    }
}
