<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * Redirects users to the password change form if they haven't changed their password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && is_null($user->password_changed_at)) {
            // Redirect to password change form
            return redirect()->route('password.change.form')->with('warning', 'Please change your password.');
        }

        return $next($request);
    }
}
