<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the authenticated user can view the user management list.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasRole('owner');
    }

    /**
     * Determine whether the authenticated user can view the read-only customer directory.
     */
    public function viewCustomers(User $authUser): bool
    {
        return $authUser->hasAnyRole(['owner', 'admin']);
    }

    /**
     * Determine whether the authenticated user can view a user record.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('owner') || $authUser->id === $targetUser->id;
    }

    /**
     * Determine whether the authenticated user can create new user accounts.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('owner');
    }

    /**
     * Determine whether the authenticated user can update the target user's role.
     * Owner cannot change their own role.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('owner') && $authUser->id !== $targetUser->id;
    }

    /**
     * Alias for role update authorization.
     */
    public function updateRole(User $authUser, User $targetUser): bool
    {
        return $this->update($authUser, $targetUser);
    }

    /**
     * Determine whether the authenticated user can delete the target user.
     * Owner cannot delete their own account.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('owner') && $authUser->id !== $targetUser->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $authUser, User $targetUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $authUser, User $targetUser): bool
    {
        return false;
    }
}
