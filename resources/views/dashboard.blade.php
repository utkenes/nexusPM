<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Workspace Dashboard" 
            description="Overview of your workspace, projects, tasks, and recent collaboration activities."
        >
            <x-slot name="actions">
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-950 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    + New Project
                </a>
            </x-slot>
        </x-section-header>
    </x-slot>

    <div class="py-12 bg-gray-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Stats Grid using Reusable Statistic Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Projects -->
                <x-statistic-card 
                    title="Active Projects" 
                    value="{{ $stats['projects_count'] }}" 
                    color="indigo"
                >
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </x-slot>
                </x-statistic-card>

                <!-- Assigned Tasks -->
                <x-statistic-card 
                    title="My Assigned Tasks" 
                    value="{{ $stats['tasks_count'] }}" 
                    color="sky"
                >
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                    </x-slot>
                </x-statistic-card>

                <!-- Completed Tasks -->
                <x-statistic-card 
                    title="Completed Tasks" 
                    value="{{ $stats['completed_count'] }}" 
                    color="emerald"
                >
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </x-slot>
                </x-statistic-card>

                <!-- Pending Tasks -->
                <x-statistic-card 
                    title="Pending Tasks" 
                    value="{{ $stats['pending_count'] }}" 
                    color="amber"
                >
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-slot>
                </x-statistic-card>
            </div>

            <!-- Two-Column Workspace Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left: Projects & Activity List (2/3 span) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Projects -->
                    <div class="bg-gray-900 shadow-sm sm:rounded-xl border border-gray-800 p-6 space-y-4">
                        <h3 class="text-base font-bold text-gray-250">Active Projects</h3>
                        @if($projects->isEmpty())
                            <x-empty-state 
                                title="No projects in this workspace" 
                                message="Create your first project to start organizing tasks and collaboration." 
                                actionUrl="{{ route('projects.create') }}" 
                                actionText="Create Project"
                                icon="folder"
                            />
                        @else
                            <div class="divide-y divide-gray-850">
                                @foreach($projects as $proj)
                                    <div class="py-4 flex justify-between items-center hover:bg-gray-800/20 px-2 rounded-lg transition-colors">
                                        <div class="space-y-1">
                                            <a href="{{ route('projects.show', $proj) }}" class="text-orange-500 hover:text-orange-400 font-bold text-base tracking-tight">
                                                {{ $proj->title }}
                                            </a>
                                            <p class="text-xs text-gray-400 line-clamp-1 leading-relaxed">{{ $proj->description }}</p>
                                        </div>
                                        <x-badge :value="$proj->status->value" type="status" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Recent Activity Section (using Spatie Activity Logs) -->
                    <div class="bg-gray-900 shadow-sm sm:rounded-xl border border-gray-800 p-6 space-y-4">
                        <h3 class="text-base font-bold text-gray-250">Recent Activity</h3>
                        @if($activities->isEmpty())
                            <div class="py-6 text-center text-sm text-gray-500">
                                No activity logged.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($activities as $activity)
                                    <div class="flex items-start space-x-3 text-sm border-b border-gray-850 pb-3 last:border-b-0 last:pb-0">
                                        <div class="mt-0.5">
                                            <x-avatar :name="$activity->causer ? $activity->causer->name : 'System'" size="xs" />
                                        </div>
                                        <div class="flex-grow">
                                            <span class="font-bold text-gray-250">{{ $activity->causer ? $activity->causer->name : 'System' }}</span>
                                            <span class="text-gray-400">{{ $activity->description }}</span>
                                            @if($activity->subject)
                                                <span class="font-semibold text-orange-500">
                                                    "{{ $activity->subject->title ?? $activity->subject->name ?? 'Resource' }}"
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-550 block mt-1">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right: My Assigned Tasks (1/3 span) -->
                <div class="space-y-8">
                    <div class="bg-gray-900 shadow-sm sm:rounded-xl border border-gray-800 p-6 space-y-4">
                        <h3 class="text-base font-bold text-gray-250">My Assigned Tasks</h3>
                        @if($assignedTasks->isEmpty())
                            <x-empty-state 
                                title="No tasks assigned" 
                                message="You have no pending tasks in this workspace." 
                                icon="tasks"
                            />
                        @else
                            <div class="space-y-4">
                                @foreach($assignedTasks as $task)
                                    <div class="p-4 border border-gray-800 rounded-xl space-y-3 hover:shadow-sm transition-all bg-gray-900/60 hover:border-orange-500/50">
                                        <div class="flex justify-between items-start">
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                                {{ $task->project->title }}
                                            </span>
                                            <x-badge :value="$task->priority->value" type="priority" />
                                        </div>
                                        <h4 class="font-bold text-gray-200 text-sm leading-snug">{{ $task->title }}</h4>
                                        <div class="flex justify-between items-center text-xs text-gray-450">
                                            <span class="flex items-center space-x-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $task->due_date ? $task->due_date->format('M d') : 'No due date' }}</span>
                                            </span>
                                            <x-badge :value="$task->status->value" type="status" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
