<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HEI;
use Illuminate\Auth\Access\HandlesAuthorization;

class HEIPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the HEI import form.
     */
    public function viewImportForm(User $user)
    {
        return $user->hasRole('admin'); // Only allow 'admin' role
    }

    /**
     * Determine if the user can import HEIs.
     */
    public function import(User $user)
    {
        return $user->hasRole('admin'); // Only allow 'admin' role
    }
}
