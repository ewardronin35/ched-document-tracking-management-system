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
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\FailedLoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Models\AuditLog;

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
        Fortify::resetUserPasswordsUsing(MarkEmailVerifiedOnReset::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
            );
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
        $this->app->singleton(LoginResponse::class, function ($app) {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    // If the request expects JSON, return a JSON response
                    if ($request->wantsJson()) {
                        // Determine redirect URL; adjust as needed for role-based routing
                        $redirectUrl = route('dashboard'); 
                        return response()->json([
                            'success'   => true,
                            'redirect'  => $redirectUrl,
                            'csrfToken' => csrf_token(),
                        ]);
                    }
                    // Fallback to default redirect
                    return redirect()->intended(config('fortify.home'));
                }
            };
        });
        
        $this->app->singleton(FailedLoginResponse::class, function ($app) {
            return new class implements FailedLoginResponse {
                public function toResponse($request)
                {
                    if ($request->wantsJson()) {
                        // Collect validation errors if any
                        $errors = session('errors') ? session('errors')->getBag('default')->all() : ['Invalid credentials.'];
                        return response()->json([
                            'success' => false,
                            'errors'  => $errors,
                            'csrfToken' => csrf_token(),
                        ], 422);
                    }
                    // Fallback to default redirect with errors
                    return redirect()->route('login')
                                     ->withInput($request->only('email'))
                                     ->withErrors([
                                         'email' => trans('auth.failed'),
                                     ]);
                }
            };
        });
        $this->app->singleton(LogoutResponse::class, function ($app) {
            return new class implements LogoutResponse {
                public function toResponse($request)
                {
                    return redirect('/login');
                }
            };
        });
        // Custom authentication logic with flash message on success
        Fortify::authenticateUsing(function (Request $request) {
            // 1. Look up user by email
            $user = User::where('email', $request->email)->first();
        
            // 2. Check if the user exists, can_login is true, and password is correct
            if ($user && $user->can_login && Hash::check($request->password, $user->password)) {

                AuditLog::create([
                    'user_id'    => $user->id,
                    'event_type' => 'login',
                    'description'=> 'User logged in successfully.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                session()->flash('success', 'You have successfully logged in!');
                Log::info('User Logged In:', ['email' => $user->email]);
                return $user;
            }
        
            // 3. If user is found but cannot log in
            if ($user && !$user->can_login) {
                session()->flash('error', 'Your account is currently disabled by an administrator.');
            } else {
                // Otherwise, it's just invalid credentials
                session()->flash('error', 'Invalid credentials.');
            }
        
            // 4. Return null to indicate login failure
            return null;
        });
        

    }
}
