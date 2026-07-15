<?php

namespace App\Actions\Task;

use App\Services\Task\BulkTaskService;

class BulkUpdateTasksAction
{
    public function __construct(
        protected BulkTaskService $bulkTaskService
    ) {}

    /**
     * Execute the bulk update action.
     *
     * @param array<int> $taskIds
     * @param array<string, mixed> $data
     */
    public function execute(array $taskIds, array $data): void
    {
        $this->bulkTaskService->bulkUpdate($taskIds, $data);
    }
}
