<?php

namespace App\Policies;

use App\Models\User;

class GmailPolicy
{
    public function viewHistory(User $user)
    {
        return $user->hasPermissionTo('view email history'); 
        // or $user->hasRole('admin');
    }

    public function sendEmail(User $user)
    {
        return $user->hasPermissionTo('send email'); 
        // or $user->hasRole('email_sender');
    }
}