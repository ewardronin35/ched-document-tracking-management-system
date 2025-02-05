<?php

namespace App\Policies;

use App\Models\Outgoing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutgoingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any outgoings.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can view the outgoing.
     */
    public function view(User $user, Outgoing $outgoing)
    {
        return $user->hasRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can create outgoings.
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the outgoing.
     */
    public function update(User $user, Outgoing $outgoing)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the outgoing.
     */
    public function delete(User $user, Outgoing $outgoing)
    {
        return $user->hasRole('admin');
    }

    // Add other methods if needed
}
