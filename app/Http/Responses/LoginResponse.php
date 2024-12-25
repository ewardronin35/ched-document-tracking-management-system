<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // After successful login, flash a success message:
        session()->flash('success', 'You have logged in successfully!');

        // Redirect the user to their intended location or dashboard:
        return redirect()->intended(route('dashboard'));
    }
}
