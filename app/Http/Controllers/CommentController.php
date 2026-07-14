<?php

namespace App\Http\Controllers;

use App\Actions\Comment\AddCommentAction;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreCommentRequest $request, Task $task, AddCommentAction $action): RedirectResponse
    {
        $this->authorize('view', $task);

        $action->execute($task, $request->user(), $request->validated());

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
}
