<?php

namespace Tests\Feature;

use App\Actions\Organization\CreateOrganizationAction;
use App\Enums\OrganizationRole;
use App\Models\Organization;
use App\Models\User;
use App\Services\Organization\OrganizationMembershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_organization_via_action(): void
    {
        $user = User::factory()->create();
        $action = new CreateOrganizationAction;

        $organization = $action->execute($user, [
            'name' => 'Acme Corporation',
            'slug' => 'acme-corp',
        ]);

        $this->assertInstanceOf(Organization::class, $organization);
        $this->assertEquals('Acme Corporation', $organization->name);
        $this->assertEquals('acme-corp', $organization->slug);

        // Verify owner pivot assignment
        $this->assertDatabaseHas('organization_user', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => OrganizationRole::Owner->value,
        ]);

        // Verify active organization update
        $user->refresh();
        $this->assertEquals($organization->id, $user->current_organization_id);
    }

    public function test_user_can_be_added_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        $service = new OrganizationMembershipService;

        $service->addUserToOrganization($organization, $user, OrganizationRole::Member);

        $this->assertDatabaseHas('organization_user', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => OrganizationRole::Member->value,
        ]);
    }

    public function test_user_can_switch_active_organization(): void
    {
        $user = User::factory()->create();
        $org1 = Organization::factory()->create();
        $org2 = Organization::factory()->create();

        $service = new OrganizationMembershipService;

        $service->addUserToOrganization($org1, $user, OrganizationRole::Member);
        $service->addUserToOrganization($org2, $user, OrganizationRole::Member);

        $service->switchActiveOrganization($user, $org1);
        $this->assertEquals($org1->id, $user->current_organization_id);

        $service->switchActiveOrganization($user, $org2);
        $this->assertEquals($org2->id, $user->current_organization_id);
    }

    public function test_user_cannot_switch_to_non_associated_organization(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $service = new OrganizationMembershipService;

        $this->expectException(\InvalidArgumentException::class);
        $service->switchActiveOrganization($user, $org);
    }
}
