<?php

namespace App\Services\Task;

use App\Models\Label;
use App\Models\Organization;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class LabelService
{
    /**
     * Get all labels for an organization.
     *
     * @return Collection<int, Label>
     */
    public function getOrganizationLabels(Organization $organization): Collection
    {
        return Label::where('organization_id', $organization->id)->get();
    }

    /**
     * Create a new label for an organization.
     */
    public function createLabel(Organization $organization, string $name, string $color): Label
    {
        return Label::create([
            'organization_id' => $organization->id,
            'name' => $name,
            'color' => $color,
        ]);
    }

    /**
     * Sync labels on a task.
     */
    public function syncLabels(Task $task, array $labelIds): void
    {
        $task->labels()->sync($labelIds);
    }
}
