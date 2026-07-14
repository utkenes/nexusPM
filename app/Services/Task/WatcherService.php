<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;

class WatcherService
{
    /**
     * Toggle watching status of a user for a task.
     */
    public function toggleWatcher(Task $task, User $user): bool
    {
        $attached = $task->watchers()->toggle($user->id);

        return count($attached['attached']) > 0;
    }

    /**
     * Get all watchers for a task.
     */
    public function getWatchers(Task $task)
    {
        return $task->watchers()->get();
    }
}
