<?php

namespace Tests\Feature;

use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Models\Label;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_assign_labels_to_task(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $user->organizations()->attach($org->id, ['role' => OrganizationRole::Owner->value, 'joined_at' => now()]);
        $user->update(['current_organization_id' => $org->id]);

        $project = Project::factory()->create(['organization_id' => $org->id]);
        $project->members()->attach($user->id, ['role' => ProjectRole::Admin->value, 'joined_at' => now()]);

        $task = Task::factory()->create(['project_id' => $project->id]);

        $label = Label::create([
            'organization_id' => $org->id,
            'name' => 'Bug',
            'color' => '#ef4444',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('tasks.assignLabels', $task->id), [
                'labels' => [$label->id],
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertTrue($task->labels->contains($label->id));
    }
}
