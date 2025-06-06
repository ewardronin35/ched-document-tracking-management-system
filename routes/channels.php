<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to a channel.
|
*/

// Example: Only allow the user to listen to their own private channel.
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

