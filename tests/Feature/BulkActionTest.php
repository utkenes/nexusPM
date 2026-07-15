<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Organization $organization;
    protected Project $project;
    protected Task $task1;
    protected Task $task2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->organization = Organization::factory()->create();
        $this->user->organizations()->attach($this->organization, ['role' => 'admin']);
        $this->user->update(['current_organization_id' => $this->organization->id]);

        $this->project = Project::factory()->create(['organization_id' => $this->organization->id]);
        $this->task1 = Task::factory()->create(['project_id' => $this->project->id, 'status' => TaskStatus::Todo]);
        $this->task2 = Task::factory()->create(['project_id' => $this->project->id, 'status' => TaskStatus::Todo]);
    }

    /**
     * Test bulk status update.
     */
    public function test_can_bulk_update_task_status(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('tasks.bulkUpdate'), [
            'task_ids' => [$this->task1->id, $this->task2->id],
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(TaskStatus::InProgress, $this->task1->fresh()->status);
        $this->assertEquals(TaskStatus::InProgress, $this->task2->fresh()->status);
    }

    /**
     * Test bulk assignee update.
     */
    public function test_can_bulk_update_task_assignee(): void
    {
        $newAssignee = User::factory()->create();
        $this->organization->users()->attach($newAssignee, ['role' => 'member']);

        $response = $this->actingAs($this->user)->postJson(route('tasks.bulkUpdate'), [
            'task_ids' => [$this->task1->id, $this->task2->id],
            'assigned_to' => $newAssignee->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals($newAssignee->id, $this->task1->fresh()->assigned_to);
        $this->assertEquals($newAssignee->id, $this->task2->fresh()->assigned_to);
    }

    /**
     * Test bulk deletion.
     */
    public function test_can_bulk_delete_tasks(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('tasks.bulkUpdate'), [
            'task_ids' => [$this->task1->id, $this->task2->id],
            'delete' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('tasks', ['id' => $this->task1->id]);
        $this->assertSoftDeleted('tasks', ['id' => $this->task2->id]);
    }

    /**
     * Test bulk action requires validation.
     */
    public function test_bulk_action_validates_inputs(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('tasks.bulkUpdate'), [
            'task_ids' => [9999, 8888], // Non-existent tasks
        ]);

        $response->assertStatus(422);
    }
}
