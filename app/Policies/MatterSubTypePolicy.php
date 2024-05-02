<?php

namespace App\Policies;

use App\Models\MatterSubType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MatterSubTypePolicy
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
            $user->can('matter-types.matter-subtypes.browse') || $user->can('matter-types.matter-subtypes.read')
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MatterSubType $matterSubType): bool
    {
        return $user->can('matter-types.matter-subtypes.read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('matter-types.matter-subtypes.add');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MatterSubType $matterSubType): bool
    {
        return $user->can('matter-types.matter-subtypes.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MatterSubType $matterSubType): bool
    {
        return $user->can('matter-types.matter-subtypes.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MatterSubType $matterSubType): bool
    {
        return ($user->can('matter-types.matter-subtypes.restore') || $user->can('matter-types.matter-subtypes.delete'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MatterSubType $matterSubType): bool
    {
        return $user->can('matter-types.matter-subtypes.delete');
    }
}
