<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
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

        // Fetch Recent Activity scoped to workspace users
        $orgUserIds = $organization->users()->pluck('users.id');
        $activities = Activity::whereIn('causer_id', $orgUserIds)
            ->with('causer')
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact('organization', 'projects', 'assignedTasks', 'stats', 'activities'));
    }
}
