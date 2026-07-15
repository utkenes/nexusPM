<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Organization $organization;

    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->organization = Organization::factory()->create();
        $this->user->organizations()->attach($this->organization, ['role' => 'admin']);
        $this->user->update(['current_organization_id' => $this->organization->id]);

        $this->project = Project::factory()->create(['organization_id' => $this->organization->id]);
    }

    /**
     * Test calendar page loads successfully for authenticated user.
     */
    public function test_calendar_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertViewIs('calendar.index');
        $response->assertViewHas('currentMonthName');
    }

    /**
     * Test calendar page redirects if no organization selected.
     */
    public function test_calendar_page_redirects_if_no_organization(): void
    {
        $userWithoutOrg = User::factory()->create();

        $response = $this->actingAs($userWithoutOrg)->get(route('calendar.index'));

        $response->assertRedirect(route('organizations.index'));
        $response->assertSessionHas('warning');
    }

    /**
     * Test calendar loads specific month and lists tasks.
     */
    public function test_calendar_lists_tasks_due_in_selected_month(): void
    {
        $dueInJuly = Task::factory()->create([
            'project_id' => $this->project->id,
            'due_date' => '2026-07-15 12:00:00',
        ]);

        $dueInAugust = Task::factory()->create([
            'project_id' => $this->project->id,
            'due_date' => '2026-08-15 12:00:00',
        ]);

        $response = $this->actingAs($this->user)->get(route('calendar.index', [
            'month' => 7,
            'year' => 2026,
        ]));

        $response->assertStatus(200);

        $tasksByDay = $response->viewData('tasksByDay');
        $this->assertTrue($tasksByDay->has('2026-07-15'));
        $this->assertFalse($tasksByDay->has('2026-08-15'));
    }
}
