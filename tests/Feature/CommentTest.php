<?php

namespace Tests\Feature;

use App\Actions\Comment\AddCommentAction;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_with_attachments_via_action(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $action = new AddCommentAction;

        $comment = $action->execute($task, $user, [
            'content' => 'This is a test comment.',
            'attachments' => [
                [
                    'file_path' => 'attachments/image.png',
                    'file_name' => 'image.png',
                    'file_size' => 2048,
                    'mime_type' => 'image/png',
                ],
            ],
        ]);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('This is a test comment.', $comment->content);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($task->id, $comment->task_id);

        // Verify polymorphic attachments
        $this->assertCount(1, $comment->attachments);
        $this->assertDatabaseHas('attachments', [
            'attachable_type' => Comment::class,
            'attachable_id' => $comment->id,
            'file_name' => 'image.png',
            'user_id' => $user->id,
        ]);
    }

    public function test_comment_supports_soft_deletes(): void
    {
        $comment = Comment::factory()->create();

        $comment->delete();

        $this->assertSoftDeleted($comment);
    }
}
