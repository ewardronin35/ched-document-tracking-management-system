<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Show the password change form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Handle the password change request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update the user's password and mark email as verified
        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->email_verified_at = now(); // Mark email as verified
        $user->save();

        // Optionally, you can log the user out and require re-login
        // Auth::logout();

        // Redirect with a success message
        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
    }
}
