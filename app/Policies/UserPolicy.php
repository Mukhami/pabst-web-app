<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return (
            $user->can('users.users.read') || $user->can('users.users.browse')
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return (
            $user->can('users.users.browse') || $user->can('users.users.read')
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return (
            $user->can('users.users.add')
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return (
            $user->can('users.users.edit')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return (
            $user->can('users.users.delete')
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return (
            $user->can('users.users.delete') || $user->can('users.users.restore')
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return (
            $user->can('users.users.delete')
        );
    }
}
