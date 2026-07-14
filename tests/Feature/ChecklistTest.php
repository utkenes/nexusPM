<?php

namespace Tests\Feature;

use App\Models\ChecklistItem;
use App\Models\Task;
use App\Services\Task\TaskChecklistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_checklist_item_via_service(): void
    {
        $task = Task::factory()->create();
        $service = new TaskChecklistService;

        $item = $service->addItem($task, 'Prepare documents');

        $this->assertInstanceOf(ChecklistItem::class, $item);
        $this->assertEquals('Prepare documents', $item->title);
        $this->assertFalse($item->is_completed);
        $this->assertEquals(0, $item->sort_order);
    }

    public function test_user_can_toggle_checklist_item_status(): void
    {
        $item = ChecklistItem::factory()->create(['is_completed' => false]);
        $service = new TaskChecklistService;

        $updatedItem = $service->toggleItem($item);
        $this->assertTrue($updatedItem->is_completed);

        $updatedItem2 = $service->toggleItem($updatedItem);
        $this->assertFalse($updatedItem2->is_completed);
    }

    public function test_user_can_reorder_checklist_items(): void
    {
        $task = Task::factory()->create();
        $item1 = ChecklistItem::factory()->create(['task_id' => $task->id, 'sort_order' => 0]);
        $item2 = ChecklistItem::factory()->create(['task_id' => $task->id, 'sort_order' => 1]);

        $service = new TaskChecklistService;
        $service->reorderItems([$item2->id, $item1->id]);

        $this->assertEquals(0, $item2->refresh()->sort_order);
        $this->assertEquals(1, $item1->refresh()->sort_order);
    }
}
