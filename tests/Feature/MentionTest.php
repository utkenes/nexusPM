<?php

namespace Tests\Feature;

use App\Enums\OrganizationRole;
use App\Models\Organization;
use App\Models\User;
use App\Services\Comment\MentionParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mention_parser_service_detects_handles(): void
    {
        $org = Organization::factory()->create();
        $user1 = User::factory()->create(['name' => 'Alice Allison']);
        $user2 = User::factory()->create(['name' => 'Bob Barker']);

        $org->users()->attach($user1->id, ['role' => OrganizationRole::Owner->value, 'joined_at' => now()]);
        $org->users()->attach($user2->id, ['role' => OrganizationRole::Owner->value, 'joined_at' => now()]);

        $service = new MentionParserService;
        $matches = $service->parseMentions('Hello @alice-allison please review, also @bobbarker look at this.', $org);

        $this->assertCount(2, $matches);
        $this->assertEquals($user1->id, $matches[0]->id);
        $this->assertEquals($user2->id, $matches[1]->id);
    }

    public function test_mention_parser_highlights_handles(): void
    {
        $org = Organization::factory()->create();
        $user1 = User::factory()->create(['name' => 'Alice Allison']);
        $org->users()->attach($user1->id, ['role' => OrganizationRole::Owner->value, 'joined_at' => now()]);

        $service = new MentionParserService;
        $highlighted = $service->highlightMentions('Hello @alice-allison', $org);

        $this->assertStringContainsString('<span class="text-orange-500 font-bold">@alice-allison</span>', $highlighted);
    }
}
