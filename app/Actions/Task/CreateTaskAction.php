<?php

namespace App\Actions\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateTaskAction
{
    /**
     * Create a task under a project and handle nested checklist items.
     */
    public function execute(Project $project, User $creator, array $data): Task
    {
        return DB::transaction(function () use ($project, $creator, $data) {
            /** @var Task $task */
            $task = $project->tasks()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? TaskStatus::Todo->value,
                'priority' => $data['priority'] ?? TaskPriority::Medium->value,
                'due_date' => $data['due_date'] ?? null,
                'assigned_to' => $data['assigned_to'] ?? null,
                'created_by' => $creator->id,
            ]);

            if (isset($data['checklist_items']) && is_array($data['checklist_items'])) {
                foreach ($data['checklist_items'] as $index => $item) {
                    $task->checklistItems()->create([
                        'title' => $item['title'],
                        'is_completed' => $item['is_completed'] ?? false,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $task;
        });
    }
}
