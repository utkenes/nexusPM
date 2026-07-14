<?php

namespace App\Http\Controllers;

use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\UpdateTaskStatusAction;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        ]);

        return response()->json([
            'task' => $task,
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

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }
}
