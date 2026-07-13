<?php

namespace App\Services\Project;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;

class ProjectMembershipService
{
    /**
     * Add a user as a member to a project with a role.
     */
    public function addMemberToProject(Project $project, User $user, ProjectRole $role = ProjectRole::Member): void
    {
        // Ensure user belongs to the project's organization first.
        $belongsToOrg = $user->organizations()
            ->where('organizations.id', $project->organization_id)
            ->exists();

        if (! $belongsToOrg) {
            throw new \InvalidArgumentException('User must belong to the organization of the project.');
        }

        $project->members()->syncWithoutDetaching([
            $user->id => [
                'role' => $role->value,
                'joined_at' => now(),
            ],
        ]);
    }

    /**
     * Remove a member from a project.
     */
    public function removeMemberFromProject(Project $project, User $user): void
    {
        $project->members()->detach($user->id);
    }
}
