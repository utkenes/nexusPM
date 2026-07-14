<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddCommentAction
{
    /**
     * Add a comment to a task, handling optional attachments.
     */
    public function execute(Task $task, User $user, array $data): Comment
    {
        return DB::transaction(function () use ($task, $user, $data) {
            /** @var Comment $comment */
            $comment = $task->comments()->create([
                'user_id' => $user->id,
                'content' => $data['content'],
            ]);

            if (isset($data['attachments']) && is_array($data['attachments'])) {
                foreach ($data['attachments'] as $attachmentData) {
                    $comment->attachments()->create([
                        'user_id' => $user->id,
                        'file_path' => $attachmentData['file_path'],
                        'file_name' => $attachmentData['file_name'],
                        'file_size' => $attachmentData['file_size'],
                        'mime_type' => $attachmentData['mime_type'] ?? null,
                    ]);
                }
            }

            return $comment;
        });
    }
}
