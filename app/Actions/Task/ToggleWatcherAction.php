<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
use App\Services\Task\WatcherService;

class ToggleWatcherAction
{
    public function __construct(
        protected WatcherService $watcherService
    ) {}

    /**
     * Execute the action to toggle task watching.
     */
    public function execute(Task $task, User $user): bool
    {
        return $this->watcherService->toggleWatcher($task, $user);
    }
}
