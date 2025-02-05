<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class MarkEmailVerifiedOnReset implements ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  array  $input
     * @return void
     */
    public function reset(CanResetPasswordContract $user, array $input)
    {
        // 1) Force fill the new password
        $user->forceFill([
            'password' => Hash::make($input['password']),
            'remember_token' => Str::random(60),
        ])->save();

        // 2) Mark as verified if not verified already
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    }
}
