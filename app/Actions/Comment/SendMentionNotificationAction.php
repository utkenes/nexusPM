<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\TaskMentioned;
use Illuminate\Support\Facades\Notification;

class SendMentionNotificationAction
{
    /**
     * Send notification to parsed mentioned users.
     *
     * @param  array<int, User>  $users
     */
    public function execute(array $users, Comment $comment): void
    {
        if (empty($users)) {
            return;
        }

        Notification::send($users, new TaskMentioned($comment));
    }
}
