<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskMentioned extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Comment $comment
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $task = $this->comment->task;
        $author = $this->comment->user->name;

        return [
            'type' => 'mentioned',
            'message' => "{$author} mentioned you in task '{$task->title}'",
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'url' => route('projects.show', $task->project_id),
        ];
    }
}
