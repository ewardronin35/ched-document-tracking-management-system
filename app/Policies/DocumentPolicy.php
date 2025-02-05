<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user)
    {
        // Allow if the user has any role
        return $user->hasAnyRole(['admin', 'records', 'editor', 'viewer']); // List all roles that should have access
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document)
    {
        // Allow if user has any of the specified roles OR owns the document
        return $user->hasAnyRole(['admin', 'records', 'editor']) || $user->email === $document->email;
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document)
    {
        // Allow if user has any of the specified roles OR owns the document
        return $user->hasAnyRole(['admin', 'records', 'editor']) || $user->email === $document->email;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document)
    {
        // Allow only if user has one of the specified roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    /**
     * Determine whether the user can assign tracking to the document.
     */
    public function assignTracking(User $user, Document $document)
    {
        // Allow only if user has one of the specified roles
        return $user->hasAnyRole(['admin', 'records']);
    }

    // Add other policy methods as needed...
}
