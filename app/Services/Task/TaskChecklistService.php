<?php

namespace App\Services\Task;

use App\Models\ChecklistItem;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskChecklistService
{
    /**
     * Add a new item to the task checklist.
     */
    public function addItem(Task $task, string $title): ChecklistItem
    {
        $nextOrder = $task->checklistItems()->max('sort_order') ?? -1;

        /** @var ChecklistItem $item */
        $item = $task->checklistItems()->create([
            'title' => $title,
            'is_completed' => false,
            'sort_order' => $nextOrder + 1,
        ]);

        return $item;
    }

    /**
     * Toggle the completion status of a checklist item.
     */
    public function toggleItem(ChecklistItem $item): ChecklistItem
    {
        $item->update([
            'is_completed' => ! $item->is_completed,
        ]);

        return $item;
    }

    /**
     * Reorder checklist items.
     *
     * @param  array<int>  $orderedIds  Array of checklist item IDs in the desired order.
     */
    public function reorderItems(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                ChecklistItem::where('id', $id)->update([
                    'sort_order' => $index,
                ]);
            }
        });
    }
}
