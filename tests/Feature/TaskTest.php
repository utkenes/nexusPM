<?php

namespace Tests\Feature;

use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\UpdateTaskStatusAction;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_with_checklist_via_action(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $action = new CreateTaskAction;

        $task = $action->execute($project, $user, [
            'title' => 'Design API endpoints',
            'description' => 'Create REST API design document.',
            'status' => TaskStatus::Todo->value,
            'priority' => TaskPriority::High->value,
            'checklist_items' => [
                ['title' => 'Define resource list'],
                ['title' => 'Draft response schemas'],
            ],
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Design API endpoints', $task->title);
        $this->assertEquals(TaskStatus::Todo, $task->status);
        $this->assertEquals(TaskPriority::High, $task->priority);
        $this->assertEquals($user->id, $task->created_by);

        // Verify checklist items
        $this->assertCount(2, $task->checklistItems);
        $this->assertDatabaseHas('checklist_items', [
            'task_id' => $task->id,
            'title' => 'Define resource list',
            'sort_order' => 0,
        ]);
    }

    public function test_user_can_update_task_status_via_action(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $action = new UpdateTaskStatusAction;
        $updatedTask = $action->execute($task, TaskStatus::InProgress, $user);

        $this->assertEquals(TaskStatus::InProgress, $updatedTask->status);
        $this->assertEquals($user->id, $updatedTask->updated_by);
    }

    public function test_task_supports_soft_deletes(): void
    {
        $task = Task::factory()->create();

        $task->delete();

        $this->assertSoftDeleted($task);
    }
}
