<?php

namespace App\Http\Controllers;

use App\Actions\Project\CreateProjectAction;
use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display projects in the active organization workspace.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $organization = $user->currentOrganization;

        if (! $organization) {
            $projects = collect();
        } else {
            $projects = $organization->projects()->with('creator')->latest()->get();
        }

        return view('projects.index', compact('projects', 'organization'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(Request $request): View
    {
        $organization = $request->user()->currentOrganization;

        return view('projects.create', compact('organization'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request, CreateProjectAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $org = $user->currentOrganization;

        if (! $org) {
            return redirect()->route('organizations.index')
                ->with('error', 'Please select an active organization first.');
        }

        $project = $action->execute($org, $user, $request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', "Project {$project->title} created successfully!");
    }

    /**
     * Display the project Kanban board and members list.
     */
    public function show(Project $project, Request $request): View
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()->with(['assignee', 'creator'])->get();

        // Get members of the organization to allow adding/assigning them to projects/tasks
        $organizationMembers = $project->organization->users()->get();

        return view('projects.show', compact('project', 'tasks', 'organizationMembers'));
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $statuses = ProjectStatus::cases();

        return view('projects.edit', compact('project', 'statuses'));
    }

    /**
     * Update the project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', "Project {$project->title} updated successfully!");
    }

    /**
     * Remove the project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project soft deleted successfully!');
    }
}
