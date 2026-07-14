<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Services\Task\LabelService;

class AssignLabelsAction
{
    public function __construct(
        protected LabelService $labelService
    ) {}

    /**
     * Execute the action to sync labels to a task.
     */
    public function execute(Task $task, array $labelIds): void
    {
        $this->labelService->syncLabels($task, $labelIds);
    }
}
