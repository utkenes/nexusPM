<?php

namespace App\Policies;

use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Helper to check if a user is an admin or owner of the organization.
     */
    protected function isOrganizationManager(User $user, int $organizationId): bool
    {
        $orgUser = $user->organizations()->where('organizations.id', $organizationId)->first();
        $pivot = $orgUser?->pivot;

        /** @var object{role: string}|null $pivot */
        return $pivot && in_array($pivot->role, [
            OrganizationRole::Owner->value,
            OrganizationRole::Admin->value,
        ], true);
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        $project = $task->project;

        if ($this->isOrganizationManager($user, $project->organization_id)) {
            return true;
        }

        return $project->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        if ((int) $task->created_by === (int) $user->id) {
            return true;
        }

        $project = $task->project;

        if ($this->isOrganizationManager($user, $project->organization_id)) {
            return true;
        }

        return $project->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        if ((int) $task->created_by === (int) $user->id) {
            return true;
        }

        $project = $task->project;

        if ($this->isOrganizationManager($user, $project->organization_id)) {
            return true;
        }

        $projectUser = $project->members()->where('users.id', $user->id)->first();
        $pivot = $projectUser?->pivot;

        /** @var object{role: string}|null $pivot */
        return $pivot && $pivot->role === ProjectRole::Admin->value;
    }

    /**
     * Determine whether the user can watch the task.
     */
    public function watch(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    /**
     * Determine whether the user can manage labels on the task.
     */
    public function label(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }
}
