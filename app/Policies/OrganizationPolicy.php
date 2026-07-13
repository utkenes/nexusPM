<?php

namespace App\Policies;

use App\Enums\OrganizationRole;
use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        return $organization->users()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        $pivot = $organization->users()->where('users.id', $user->id)->first()?->pivot;

        /** @var object{role: string}|null $pivot */
        return $pivot && in_array($pivot->role, [
            OrganizationRole::Owner->value,
            OrganizationRole::Admin->value,
        ], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        $pivot = $organization->users()->where('users.id', $user->id)->first()?->pivot;

        /** @var object{role: string}|null $pivot */
        return $pivot && $pivot->role === OrganizationRole::Owner->value;
    }
}
