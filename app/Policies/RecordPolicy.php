<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Record;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('record.viewAny');
    }

    public function view(User $user, Record $record)
    {
        return $user->can('record.view');
    }

    public function create(User $user)
    {
        return $user->can('record.create');
    }

    public function update(User $user, Record $record)
    {
        return $user->can('record.edit');
    }

    public function delete(User $user, Record $record)
    {
        return $user->can('record.delete');
    }
}
