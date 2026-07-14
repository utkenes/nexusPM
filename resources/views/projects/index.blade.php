<x-app-layout>
    <!-- Page Header -->
    <x-page-header title="Projects" description="Manage projects inside your active workspace.">
        <x-slot name="actions">
            @if($organization)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-955 text-white font-semibold text-xs uppercase tracking-widest rounded-xl transition shadow-lg shadow-orange-500/20">
                    + New Project
                </a>
            @endif
        </x-slot>
    </x-page-header>

    <div class="space-y-8">
        <!-- Top statistics row -->
        @if($organization)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <x-metric-card title="Total Projects" value="{{ $projects->count() }}" />
                <x-metric-card title="Completed Projects" value="{{ $projects->where('status.value', 'completed')->count() }}" />
                <x-metric-card title="Active Workspace" value="{{ $organization->name }}" />
            </div>
        @endif

        <!-- Projects Grid -->
        <x-panel title="Projects List" :padding="false">
            <div class="p-6">
                @if(!$organization)
                    <x-empty-state 
                        title="No active workspace" 
                        message="Please switch to or create an organization workspace to manage projects." 
                        actionUrl="{{ route('organizations.index') }}" 
                        actionText="Switch Workspace"
                        icon="folder"
                    />
                @elseif($projects->isEmpty())
                    <x-empty-state 
                        title="No projects in this workspace" 
                        message="Create your first project under {{ $organization->name }} workspace." 
                        actionUrl="{{ route('projects.create') }}" 
                        actionText="Create Project"
                        icon="folder"
                    />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $proj)
                            <x-project-card :project="$proj" />
                        @endforeach
                    </div>
                @endif
            </div>
        </x-panel>
    </div>
</x-app-layout>
