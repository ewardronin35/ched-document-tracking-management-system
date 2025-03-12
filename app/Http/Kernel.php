<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Incoming;
use App\Models\Outgoing;
use App\Models\User;
use App\Notifications\UnfilledRowsNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class Kernel extends ConsoleKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $commands = [
        \App\Console\Commands\UpdateIncomingQuarter::class,
        \App\Console\Commands\ClearLog::class,

    ];
    
    protected $middleware = [
        // ... existing middleware
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, 
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,  
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,  
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class, 
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,  
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Spatie's middleware
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,  
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,  
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,  
    ];
    protected function schedule(Schedule $schedule)
{
    // For testing purposes, run every minute:
    $schedule->call(function () {
        $unfilledIncomingCount = Incoming::where(function($q) {
            $q->whereNull('sender_name')
              ->orWhere('sender_name', '');
        })->count();

        $unfilledOutgoingCount = Outgoing::where(function($q) {
            $q->whereNull('addressed_to')
              ->orWhere('addressed_to', '');
        })->count();

        if ($unfilledIncomingCount > 0 || $unfilledOutgoingCount > 0) {
            $message = "Reminder: You have {$unfilledIncomingCount} unfilled incoming row(s) and {$unfilledOutgoingCount} unfilled outgoing row(s).";
            
            $admins = User::role('admin')->get();
            Notification::send($admins, new UnfilledRowsNotification($message));
        }
        Log::info('Scheduled task triggered at ' . now());

    })->everyMinute();
}

protected function commands()
{
    $this->load(__DIR__.'/Commands');
    require base_path('routes/console.php');
}
}
