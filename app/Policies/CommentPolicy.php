<?php

namespace App\Policies;

use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
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
     * Determine whether the user can view the comment.
     */
    public function view(User $user, Comment $comment): bool
    {
        $project = $comment->task->project;

        if ($this->isOrganizationManager($user, $project->organization_id)) {
            return true;
        }

        return $project->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Only the creator can update a comment.
        return (int) $comment->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ((int) $comment->user_id === (int) $user->id) {
            return true;
        }

        $project = $comment->task->project;

        if ($this->isOrganizationManager($user, $project->organization_id)) {
            return true;
        }

        $projectUser = $project->members()->where('users.id', $user->id)->first();
        $pivot = $projectUser?->pivot;

        /** @var object{role: string}|null $pivot */
        return $pivot && $pivot->role === ProjectRole::Admin->value;
    }
}
