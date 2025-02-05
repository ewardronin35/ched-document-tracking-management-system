<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // This registers the broadcasting authentication routes (with only the 'web' middleware).
        Broadcast::routes(['middleware' => ['web']]);
        require base_path('routes/channels.php');
    }
}
