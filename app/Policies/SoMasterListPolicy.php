<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SoMasterList;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoMasterListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any SoMasterList records.
     */
    public function viewAny(User $user): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can view the SoMasterList.
     */
    public function view(User $user, SoMasterList $soMasterList): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can create SoMasterList records.
     */
    public function create(User $user): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can update the SoMasterList.
     */
    public function update(User $user, SoMasterList $soMasterList): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can delete the SoMasterList.
     */
    public function delete(User $user, SoMasterList $soMasterList): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can restore the SoMasterList.
     */
    public function restore(User $user, SoMasterList $soMasterList): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can permanently delete the SoMasterList.
     */
    public function forceDelete(User $user, SoMasterList $soMasterList): bool
    {
        // Allow only 'admin' or 'records' roles
        return $user->hasAnyRole(['admin', 'records']);
    }
}
