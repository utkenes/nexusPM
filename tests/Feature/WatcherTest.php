<?php

namespace Tests\Feature;

use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_watching_task(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $user->organizations()->attach($org->id, ['role' => OrganizationRole::Owner->value, 'joined_at' => now()]);
        $user->update(['current_organization_id' => $org->id]);

        $project = Project::factory()->create(['organization_id' => $org->id]);
        $project->members()->attach($user->id, ['role' => ProjectRole::Admin->value, 'joined_at' => now()]);

        $task = Task::factory()->create(['project_id' => $project->id]);

        // Toggle watch on (acting as user)
        $response = $this->actingAs($user)
            ->postJson(route('tasks.toggleWatch', $task->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_watching' => true]);
        $this->assertTrue($task->watchers->contains($user->id));

        // Toggle watch off
        $response = $this->actingAs($user)
            ->postJson(route('tasks.toggleWatch', $task->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_watching' => false]);
        $this->assertFalse($task->fresh()->watchers->contains($user->id));
    }
}
