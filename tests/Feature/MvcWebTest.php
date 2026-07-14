<?php

namespace Tests\Feature;

use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Enums\TaskStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\Organization\OrganizationMembershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MvcWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_without_org_is_redirected_to_organizations(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('organizations.index'));
    }

    public function test_authenticated_user_can_view_dashboard_with_active_org(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();

        $membership = new OrganizationMembershipService;
        $membership->addUserToOrganization($org, $user, OrganizationRole::Member);
        $membership->switchActiveOrganization($user, $org);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee($org->name);
    }

    public function test_user_can_view_kanban_board(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $project = Project::factory()->create(['organization_id' => $org->id]);

        $membership = new OrganizationMembershipService;
        $membership->addUserToOrganization($org, $user, OrganizationRole::Member);
        $membership->switchActiveOrganization($user, $org);

        // Add user as admin to project so policy allows viewing it
        $project->members()->attach($user->id, ['role' => ProjectRole::Admin->value]);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee($project->title);
    }

    public function test_user_can_update_task_status_via_ajax(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $project = Project::factory()->create(['organization_id' => $org->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'created_by' => $user->id]);

        $membership = new OrganizationMembershipService;
        $membership->addUserToOrganization($org, $user, OrganizationRole::Member);
        $membership->switchActiveOrganization($user, $org);
        $project->members()->attach($user->id, ['role' => ProjectRole::Admin->value]);

        $response = $this->actingAs($user)->patchJson(route('tasks.updateStatus', $task), [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(TaskStatus::InProgress, $task->refresh()->status);
    }
}
