<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Define the reset password view
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', [
                'token' => $request->route('token'),
                'email' => $request->email,
            ]);
        });

        // Define the forgot password view (optional, if customized)
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
    }
}
