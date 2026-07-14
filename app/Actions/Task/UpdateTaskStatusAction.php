<?php

namespace App\Actions\Task;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateTaskStatusAction
{
    /**
     * Update the status of a task and set the updater.
     */
    public function execute(Task $task, TaskStatus $status, User $updater): Task
    {
        return DB::transaction(function () use ($task, $status, $updater) {
            $task->update([
                'status' => $status->value,
                'updated_by' => $updater->id,
            ]);

            return $task;
        });
    }
}
