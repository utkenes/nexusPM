<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with scoped workspace statistics.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $organization = $user->currentOrganization;

        if (! $organization) {
            return redirect()->route('organizations.index')
                ->with('warning', 'Please select or create an organization first.');
        }

        $projects = $organization->projects()->latest()->get();
        $projectIds = $projects->pluck('id');

        // Calculate completion rates for projects
        $projects->each(function ($project) {
            $totalTasks = $project->tasks()->count();
            $completedTasks = $project->tasks()->where('status', TaskStatus::Done)->count();
            $project->completion_percentage = $totalTasks > 0
                ? (int) round(($completedTasks / $totalTasks) * 100)
                : 0;
            $project->total_tasks_count = $totalTasks;
            $project->completed_tasks_count = $completedTasks;
        });

        $assignedTasks = Task::where('assigned_to', $user->id)
            ->whereIn('project_id', $projectIds)
            ->with(['project', 'assignee'])
            ->latest()
            ->get();

        $stats = [
            'projects_count' => $projects->count(),
            'tasks_count' => $assignedTasks->count(),
            'completed_count' => $assignedTasks->where('status', TaskStatus::Done)->count(),
            'pending_count' => $assignedTasks->where('status', '!=', TaskStatus::Done)->count(),
        ];

        $stats['completion_rate'] = $stats['tasks_count'] > 0
            ? (int) round(($stats['completed_count'] / $stats['tasks_count']) * 100)
            : 0;

        // Fetch overdue tasks assigned to the current user
        $overdueTasks = Task::where('assigned_to', $user->id)
            ->whereIn('project_id', $projectIds)
            ->where('status', '!=', TaskStatus::Done)
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->with('project')
            ->get();

        // Fetch Recent Activity scoped to workspace users
        $orgUserIds = $organization->users()->pluck('users.id');
        $activities = Activity::whereIn('causer_id', $orgUserIds)
            ->with('causer')
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact('organization', 'projects', 'assignedTasks', 'stats', 'activities', 'overdueTasks'));
    }
}
