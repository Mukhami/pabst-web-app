<?php

namespace App\Policies;

use App\Models\MatterRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MatterRequestPolicy
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
            $user->can('matters.requests.browse') || $user->can('matters.requests.read')
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MatterRequest $matterRequest): bool
    {
        return $user->can('matters.requests.read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('matters.requests.add');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MatterRequest $matterRequest): bool
    {
        if ($matterRequest->conducted_by === auth()->id() or $matterRequest->responsible_attorney_id === auth()->id()){
            return true;
        } else {
            return false;
        }
        //return $user->can('matters.requests.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MatterRequest $matterRequest): bool
    {
        return $user->can('matters.requests.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MatterRequest $matterRequest): bool
    {
        return (
            $user->can('matters.requests.restore') ||  $user->can('matters.requests.delete')
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MatterRequest $matterRequest): bool
    {
        return $user->can('matters.requests.delete');
    }
}
