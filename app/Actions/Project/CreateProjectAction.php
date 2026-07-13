<?php

namespace App\Actions\Project;

use App\Enums\ProjectRole;
use App\Enums\ProjectStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    /**
     * Create a project under the active organization and register the creator as admin.
     */
    public function execute(Organization $organization, User $creator, array $data): Project
    {
        return DB::transaction(function () use ($organization, $creator, $data) {
            /** @var Project $project */
            $project = $organization->projects()->create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? ProjectStatus::Active->value,
                'start_date' => $data['start_date'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'created_by' => $creator->id,
            ]);

            $project->members()->attach($creator->id, [
                'role' => ProjectRole::Admin->value,
                'joined_at' => now(),
            ]);

            return $project;
        });
    }
}
