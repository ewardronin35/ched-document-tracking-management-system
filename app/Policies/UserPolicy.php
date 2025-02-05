<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authenticated user can view permissions of the given user.
     */
    public function viewPermissions(User $authUser, User $user)
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the authenticated user can update permissions of the given user.
     */
    public function updatePermissions(User $authUser, User $user)
    {
        return $authUser->hasRole('admin');
    }
}
