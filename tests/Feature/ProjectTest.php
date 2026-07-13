<?php

namespace Tests\Feature;

use App\Actions\Project\CreateProjectAction;
use App\Enums\OrganizationRole;
use App\Enums\ProjectRole;
use App\Enums\ProjectStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\Organization\OrganizationMembershipService;
use App\Services\Project\ProjectMembershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project_via_action(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $action = new CreateProjectAction;

        $project = $action->execute($organization, $user, [
            'title' => 'Launch Campaign',
            'slug' => 'launch-campaign',
            'description' => 'A campaign for project launching.',
            'status' => ProjectStatus::Active->value,
        ]);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Launch Campaign', $project->title);
        $this->assertEquals('launch-campaign', $project->slug);
        $this->assertEquals($organization->id, $project->organization_id);
        $this->assertEquals($user->id, $project->created_by);

        // Verify creator registered as admin
        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => ProjectRole::Admin->value,
        ]);
    }

    public function test_member_can_be_added_to_project(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->create(['organization_id' => $organization->id]);

        $orgService = new OrganizationMembershipService;
        $projService = new ProjectMembershipService;

        // Must join organization first
        $orgService->addUserToOrganization($organization, $user, OrganizationRole::Member);

        $projService->addMemberToProject($project, $user, ProjectRole::Member);

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => ProjectRole::Member->value,
        ]);
    }

    public function test_user_outside_organization_cannot_join_project(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->create(['organization_id' => $organization->id]);

        $projService = new ProjectMembershipService;

        $this->expectException(\InvalidArgumentException::class);
        $projService->addMemberToProject($project, $user, ProjectRole::Member);
    }
}
