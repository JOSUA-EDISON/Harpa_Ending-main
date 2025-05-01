<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Process the contact form submission
        // You can add code here to send an email, save to database, etc.

        // Redirect back with a success message
        return redirect()->back()->with('status', 'Pesan Anda telah dikirim. Terima kasih!');
    }
}