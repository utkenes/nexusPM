<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_attachment_belongs_to_user_and_can_be_polymorphic(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $comment = Comment::factory()->create();

        // Attach to Task
        $taskAttachment = Attachment::create([
            'user_id' => $user->id,
            'attachable_type' => Task::class,
            'attachable_id' => $task->id,
            'file_path' => 'attachments/task_spec.docx',
            'file_name' => 'task_spec.docx',
            'file_size' => 4096,
            'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);

        // Attach to Comment
        $commentAttachment = Attachment::create([
            'user_id' => $user->id,
            'attachable_type' => Comment::class,
            'attachable_id' => $comment->id,
            'file_path' => 'attachments/comment_err.log',
            'file_name' => 'comment_err.log',
            'file_size' => 512,
            'mime_type' => 'text/plain',
        ]);

        $this->assertInstanceOf(Attachment::class, $taskAttachment);
        $this->assertEquals(Task::class, $taskAttachment->attachable_type);
        $this->assertEquals($task->id, $taskAttachment->attachable_id);
        $this->assertEquals($user->id, $taskAttachment->user_id);

        $this->assertInstanceOf(Attachment::class, $commentAttachment);
        $this->assertEquals(Comment::class, $commentAttachment->attachable_type);
        $this->assertEquals($comment->id, $commentAttachment->attachable_id);
        $this->assertEquals($user->id, $commentAttachment->user_id);
    }
}
