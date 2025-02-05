<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\MarkEmailVerifiedOnReset;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
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
    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::resetUserPasswordsUsing(\App\Actions\Fortify\MarkEmailVerifiedOnReset::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Corrected authenticateUsing callback
        Fortify::authenticateUsing(function (Request $request) {
            // Retrieve the user by email
            $user = User::where('email', $request->email)->first();

            // Verify the password
            if ($user && Hash::check($request->password, $user->password)) {
                // Optionally set a session flash message for Toastr
                session()->flash('success', 'You have successfully logged in!');

                // Optionally log the login event
                Log::info('User Logged In: ', ['email' => $user->email]);

                return $user; // Return the User instance
            }

            return null; // Authentication failed
        });
    }
}
