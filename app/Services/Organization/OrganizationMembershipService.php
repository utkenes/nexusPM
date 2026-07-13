<?php

namespace App\Services\Organization;

use App\Enums\OrganizationRole;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationMembershipService
{
    /**
     * Add a user to an organization with a specific role.
     */
    public function addUserToOrganization(Organization $organization, User $user, OrganizationRole $role): void
    {
        $organization->users()->syncWithoutDetaching([
            $user->id => [
                'role' => $role->value,
                'joined_at' => now(),
            ],
        ]);
    }

    /**
     * Remove a user from an organization and reset active context if needed.
     */
    public function removeUserFromOrganization(Organization $organization, User $user): void
    {
        DB::transaction(function () use ($organization, $user) {
            $organization->users()->detach($user->id);

            // If the deleted organization was active, reset it to another one or null.
            if ((int) $user->current_organization_id === (int) $organization->id) {
                /** @var Organization|null $nextOrg */
                $nextOrg = $user->organizations()->first();
                $user->update([
                    'current_organization_id' => $nextOrg?->id,
                ]);
            }
        });
    }

    /**
     * Switch user's active organization context.
     */
    public function switchActiveOrganization(User $user, Organization $organization): void
    {
        // Ensure user belongs to the target organization before switching.
        if (! $user->organizations()->where('organizations.id', $organization->id)->exists()) {
            throw new \InvalidArgumentException('User does not belong to the target organization.');
        }

        $user->update([
            'current_organization_id' => $organization->id,
        ]);
    }
}
