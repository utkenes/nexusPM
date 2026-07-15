<?php

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class BulkTaskService
{
    /**
     * Perform bulk updates on an array of tasks.
     *
     * @param array<int> $taskIds
     * @param array<string, mixed> $data
     */
    public function bulkUpdate(array $taskIds, array $data): void
    {
        DB::transaction(function () use ($taskIds, $data) {
            if (!empty($data['delete'])) {
                // Fetch tasks to trigger model events (like activity logging, etc. if required)
                $tasks = Task::whereIn('id', $taskIds)->get();
                foreach ($tasks as $task) {
                    $task->delete();
                }
                return;
            }

            $updateData = [];

            if (array_key_exists('status', $data) && !is_null($data['status'])) {
                $updateData['status'] = $data['status'];
            }

            if (array_key_exists('assigned_to', $data)) {
                $updateData['assigned_to'] = $data['assigned_to'];
            }

            if (!empty($updateData)) {
                // Update individually if we want model events, or in batch for performance
                // In standard Laravel, bulk update via Eloquent query builder does not trigger model events.
                // Let's do batch update for performance as it is standard for bulk actions.
                Task::whereIn('id', $taskIds)->update($updateData);
            }
        });
    }
}
