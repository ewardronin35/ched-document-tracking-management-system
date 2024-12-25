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
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
    }
}
