<?php

namespace App\Http\Controllers;

use App\Actions\Task\AssignLabelsAction;
use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\ToggleWatcherAction;
use App\Actions\Task\UpdateTaskStatusAction;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;
use App\Services\Comment\MentionParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\Activitylog\Models\Activity;

class TaskController extends Controller
{
    /**
     * Store a newly created task under a project.
     */
    public function store(StoreTaskRequest $request, Project $project, CreateTaskAction $action): RedirectResponse
    {
        $this->authorize('update', $project);

        $action->execute($project, $request->user(), $request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task created successfully!');
    }

    /**
     * Return task details as JSON for modal injection.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $task->load([
            'checklistItems' => function ($query) {
                $query->orderBy('sort_order');
            },
            'comments.user',
            'assignee',
            'creator',
            'labels',
            'watchers',
        ]);

        // Fetch organization context
        $organization = $task->project->organization;
        $orgLabels = Label::where('organization_id', $organization->id)->get();
        $members = $organization->users()->get();

        // Fetch activity timeline for this task
        $activities = Activity::where('subject_type', Task::class)
            ->where('subject_id', $task->id)
            ->with('causer')
            ->latest()
            ->get();

        // Highlight mentions in comment content
        $mentionService = app(\App\Services\Comment\MentionParserService::class);
        /** @var \App\Models\Comment $comment */
        foreach ($task->comments as $comment) {
            $comment->content = $mentionService->highlightMentions($comment->content, $organization);
        }

        return response()->json([
            'task' => $task,
            'org_labels' => $orgLabels,
            'members' => $members,
            'activities' => $activities,
            'is_watching' => $task->watchers->contains(auth()->id()),
        ]);
    }

    /**
     * Update task details (title, description, assignee).
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $oldAssigneeId = $task->assigned_to;
        $task->update($data);

        // If assignee changed, notify the new assignee
        if (isset($data['assigned_to']) && $data['assigned_to'] != $oldAssigneeId) {
            $newAssignee = User::find($data['assigned_to']);
            if ($newAssignee) {
                $newAssignee->notify(new TaskAssigned($task));
            }
        }

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * Toggle watching status of a task for the authenticated user.
     */
    public function toggleWatch(Task $task, ToggleWatcherAction $action): JsonResponse
    {
        $this->authorize('watch', $task);

        $isWatching = $action->execute($task, auth()->user());

        return response()->json([
            'success' => true,
            'is_watching' => $isWatching,
            'watchers' => $task->watchers()->get(),
        ]);
    }

    /**
     * Assign labels to a task.
     */
    public function assignLabels(Request $request, Task $task, AssignLabelsAction $action): JsonResponse
    {
        $this->authorize('label', $task);

        $request->validate([
            'labels' => ['array'],
            'labels.*' => ['exists:labels,id'],
        ]);

        $action->execute($task, $request->input('labels', []));

        return response()->json([
            'success' => true,
            'labels' => $task->labels()->get(),
        ]);
    }

    /**
     * Update task status (primarily for Kanban drag-and-drop AJAX).
     */
    public function updateStatus(Request $request, Task $task, UpdateTaskStatusAction $action): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => ['required', 'string'],
        ]);

        $status = TaskStatus::tryFrom($request->status);

        if (! $status) {
            return response()->json(['error' => 'Invalid status.'], 422);
        }

        $action->execute($task, $status, $request->user());

        // Dispatch TaskCompleted notification if the new status is Done
        if ($status === TaskStatus::Done) {
            // Notify task watchers
            $watchers = $task->watchers()->get();
            Notification::send($watchers, new TaskCompleted($task));
        }

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }
}
