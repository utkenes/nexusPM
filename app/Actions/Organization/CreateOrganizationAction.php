<?php

namespace App\Actions\Organization;

use App\Enums\OrganizationRole;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateOrganizationAction
{
    /**
     * Create an organization, attach the owner, and set it as active.
     */
    public function execute(User $owner, array $data): Organization
    {
        return DB::transaction(function () use ($owner, $data) {
            $organization = Organization::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
            ]);

            $organization->users()->attach($owner->id, [
                'role' => OrganizationRole::Owner->value,
                'joined_at' => now(),
            ]);

            $owner->update([
                'current_organization_id' => $organization->id,
            ]);

            return $organization;
        });
    }
}
