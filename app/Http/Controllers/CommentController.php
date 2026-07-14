<?php

namespace App\Http\Controllers;

use App\Actions\Comment\AddCommentAction;
use App\Actions\Comment\SendMentionNotificationAction;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Task;
use App\Notifications\CommentAdded;
use App\Services\Comment\MentionParserService;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreCommentRequest $request, Task $task, AddCommentAction $action)
    {
        $this->authorize('view', $task);

        $comment = $action->execute($task, $request->user(), $request->validated());

        // Parse mentions
        $mentionService = app(MentionParserService::class);
        $mentionedUsers = $mentionService->parseMentions($comment->content, $task->project->organization);

        // Notify mentioned users
        $sendMentionAction = app(SendMentionNotificationAction::class);
        $sendMentionAction->execute($mentionedUsers, $comment);

        // Notify task watchers (excluding the author and already matched mentioned users)
        $watchers = $task->watchers()
            ->where('users.id', '!=', $request->user()->id)
            ->whereNotIn('users.id', collect($mentionedUsers)->pluck('id'))
            ->get();

        Notification::send($watchers, new CommentAdded($comment));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}
