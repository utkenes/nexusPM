<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_retrieve_notifications_as_json(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        // Dispatch a test notification
        $user->notify(new TaskAssigned($task));

        $response = $this->actingAs($user)
            ->getJson(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'notifications',
            'unread_count',
        ]);
        $this->assertEquals(1, $response->json('unread_count'));
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $user->notify(new TaskAssigned($task));
        $notification = $user->unreadNotifications()->first();

        $response = $this->actingAs($user)
            ->postJson(route('notifications.read', $notification->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_user_cannot_mark_other_users_notification_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create();

        $otherUser->notify(new TaskAssigned($task));
        $notification = $otherUser->unreadNotifications()->first();

        $response = $this->actingAs($user)
            ->postJson(route('notifications.read', $notification->id));

        $response->assertStatus(403);
    }
}
