<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cav;
use Illuminate\Auth\Access\HandlesAuthorization;

class CavPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any CAVs.
     */
    public function viewAny(User $user)
    {
        return $user->can('cav.viewAny');
    }

    /**
     * Determine whether the user can view the CAV.
     */
    public function view(User $user, Cav $cav)
    {
        return $user->can('cav.view');
    }

    /**
     * Determine whether the user can create CAVs.
     */
    public function create(User $user)
    {
        return $user->can('cav.create');
    }

    /**
     * Determine whether the user can update the CAV.
     */
    public function update(User $user, Cav $cav)
    {
        return $user->can('cav.edit');
    }

    /**
     * Determine whether the user can delete the CAV.
     */
    public function delete(User $user, Cav $cav)
    {
        return $user->can('cav.delete');
    }

    /**
     * Determine whether the user can import CAVs.
     */
    public function import(User $user)
    {
        return $user->can('cav.import');
    }

    /**
     * Determine whether the user can export CAVs.
     */
    public function export(User $user)
    {
        return $user->can('cav.export');
    }

    // Add more methods as needed
}
