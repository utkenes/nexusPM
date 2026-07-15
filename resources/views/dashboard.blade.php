<x-app-layout>
    <!-- Header Page Header -->
    <x-page-header 
        title="Workspace Dashboard" 
        description="Overview of your workspace, projects, tasks, and recent collaboration activities."
    >
        <x-slot name="actions">
            @if($organization)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-950 text-white font-bold text-xs uppercase tracking-widest rounded-xl transition duration-150 shadow-lg shadow-orange-500/20">
                    + New Project
                </a>
            @endif
        </x-slot>
    </x-page-header>

    <div class="space-y-8">
        <!-- Stats Grid using Reusable Metric Cards -->
        <x-stat-grid>
            <!-- Projects -->
            <x-metric-card 
                title="Active Projects" 
                value="{{ $stats['projects_count'] }}" 
                trend="Live"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </x-slot>
            </x-metric-card>

            <!-- My Tasks -->
            <x-metric-card 
                title="My Assigned Tasks" 
                value="{{ $stats['tasks_count'] }}" 
                trend="Active"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </x-slot>
            </x-metric-card>

            <!-- Completed Tasks -->
            <x-metric-card 
                title="Completed Tasks" 
                value="{{ $stats['completed_count'] }}" 
                trend="100% Verified"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </x-slot>
            </x-metric-card>

            <!-- Pending Tasks -->
            <x-metric-card 
                title="Pending Tasks" 
                value="{{ $stats['pending_count'] }}" 
                trend="In Queue"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </x-slot>
            </x-metric-card>
        </x-stat-grid>

        <!-- Information-Dense Columns Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left: Active Projects & Recent Activity (Span 2) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Active Projects -->
                <x-panel title="Active Projects" :padding="false">
                    @if($projects->isEmpty())
                        <div class="p-6">
                            <x-empty-state 
                                title="No projects workspace found" 
                                message="Create your first project to start organizing tasks and collaboration." 
                                actionUrl="{{ route('projects.create') }}" 
                                actionText="Create Project"
                                icon="folder"
                            />
                        </div>
                    @else
                        <div class="divide-y divide-gray-850">
                            @foreach($projects as $proj)
                                <div class="px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 hover:bg-gray-850/40 transition duration-150">
                                    <div class="space-y-1 flex-grow">
                                        <a href="{{ route('projects.show', $proj) }}" class="text-orange-500 hover:text-orange-400 font-bold text-sm tracking-tight transition">
                                            {{ $proj->title }}
                                        </a>
                                        <p class="text-xs text-gray-400 line-clamp-1 leading-relaxed">{{ $proj->description }}</p>
                                    </div>
                                    <div class="flex items-center space-x-6 shrink-0 justify-between sm:justify-end">
                                        <!-- Progress Bar -->
                                        <div class="w-24 space-y-1">
                                            <div class="flex justify-between text-[9px] font-black text-gray-500 uppercase tracking-widest">
                                                <span>Progress</span>
                                                <span>{{ $proj->completion_percentage }}%</span>
                                            </div>
                                            <x-progress-bar :value="$proj->completion_percentage" :max="100" />
                                        </div>
                                        <x-badge :value="$proj->status->value" type="status" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-panel>

                <!-- Recent Activity Feed -->
                <x-panel title="Recent Activity">
                    @if($activities->isEmpty())
                        <div class="py-6 text-center text-xs text-gray-500 italic">
                            No activities logged yet.
                        </div>
                    @else
                        <div class="flow-root">
                            <div class="-mb-8">
                                @foreach($activities as $activity)
                                    <x-activity-item :activity="$activity" :show-line="!$loop->last" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-panel>

            </div>

            <!-- Right: Team Members, Deadlines & Workspace Summary -->
            <div class="space-y-8">
                
                <!-- Productivity Completion Rate Widget -->
                <x-panel title="My Productivity">
                    <div class="flex items-center justify-between p-2">
                        <div class="space-y-1 flex-grow">
                            <span class="text-xs font-bold text-gray-400 block">Task Completion Rate</span>
                            <span class="text-2xl font-black text-white tracking-tight">{{ $stats['completion_rate'] }}%</span>
                            <span class="text-[10px] text-gray-550 block font-semibold mt-0.5">Done: {{ $stats['completed_count'] }} / Total: {{ $stats['tasks_count'] }}</span>
                        </div>
                        
                        <!-- Circular visual progress bar via Tailwind gradients -->
                        <div class="relative h-16 w-16 flex items-center justify-center shrink-0">
                            <!-- SVG progress ring -->
                            <svg class="h-16 w-16 transform -rotate-90">
                                <circle cx="32" cy="32" r="26" stroke="#1f2937" stroke-width="4" fill="transparent" />
                                <circle cx="32" cy="32" r="26" stroke="#ea580c" stroke-width="4" fill="transparent" 
                                    stroke-dasharray="163.36" 
                                    stroke-dashoffset="{{ 163.36 - (163.36 * $stats['completion_rate']) / 100 }}" 
                                    stroke-linecap="round"
                                />
                            </svg>
                            <span class="absolute text-[10px] font-black text-gray-200">{{ $stats['completion_rate'] }}%</span>
                        </div>
                    </div>
                </x-panel>

                <!-- Overdue Tasks Warning Widget -->
                @if($overdueTasks->isNotEmpty())
                    <x-panel title="⚠️ Overdue Tasks">
                        <div class="space-y-3">
                            @foreach($overdueTasks as $task)
                                <div class="p-3 bg-red-950/10 border border-red-900/30 rounded-xl space-y-1.5 hover:bg-red-950/20 transition-all duration-150">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-red-400 uppercase tracking-widest">
                                            Overdue - {{ $task->due_date->diffForHumans() }}
                                        </span>
                                        <x-badge :value="$task->priority->value" type="priority" />
                                    </div>
                                    <h4 class="font-bold text-gray-200 text-xs truncate leading-snug">
                                        <a href="{{ route('projects.show', $task->project) }}" class="hover:text-red-400 transition">
                                            {{ $task->title }}
                                        </a>
                                    </h4>
                                    <span class="text-[9px] text-gray-500 font-bold block">{{ $task->project->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-panel>
                @endif
                
                <!-- Workspace Summary Panel -->
                <x-panel title="Workspace Info">
                    <div class="space-y-3.5">
                        <x-task-meta label="Current Org" :value="$organization ? $organization->name : 'N/A'" />
                        <x-task-meta label="Org Slug" :value="$organization ? $organization->slug : 'N/A'" />
                        <x-task-meta label="Total Members" :value="$organization ? $organization->users()->count() : '0'" />
                        <x-task-meta label="My Role" :value="$organization ? Auth::user()->organizations()->where('organization_id', $organization->id)->first()?->pivot?->role ?? 'Member' : 'N/A'" />
                    </div>
                </x-panel>

                <!-- Team Members Panel -->
                <x-panel title="Team Members">
                    @if($organization)
                        <div class="divide-y divide-gray-850">
                            @foreach($organization->users as $member)
                                <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                                    <div class="flex items-center space-x-2.5">
                                        <x-avatar :name="$member->name" size="xs" />
                                        <div class="text-xs font-semibold">
                                            <span class="text-gray-200 block truncate leading-snug">{{ $member->name }}</span>
                                            <span class="text-gray-500 block truncate leading-none mt-0.5">{{ $member->email }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">
                                        {{ $member->pivot->role }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-xs text-gray-550 py-4 italic">No active workspace.</div>
                    @endif
                </x-panel>

                <!-- My Assigned Tasks Panel -->
                <x-panel title="My Pending Tasks">
                    @if($assignedTasks->isEmpty())
                        <div class="text-center text-xs text-gray-550 py-4 italic">No pending tasks.</div>
                    @else
                        <div class="space-y-3">
                            @foreach($assignedTasks as $task)
                                <div class="p-3.5 bg-gray-950/40 border border-gray-800 rounded-xl space-y-2 hover:border-orange-500/30 transition">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest truncate max-w-[120px]">
                                            {{ $task->project->title }}
                                        </span>
                                        <x-badge :value="$task->priority->value" type="priority" />
                                    </div>
                                    <h4 class="font-bold text-gray-200 text-xs leading-snug truncate">
                                        <a href="{{ route('projects.show', $task->project) }}" class="hover:text-orange-500 transition">
                                            {{ $task->title }}
                                        </a>
                                    </h4>
                                    <div class="flex justify-between items-center text-[10px] text-gray-450">
                                        <span>{{ $task->due_date ? $task->due_date->format('M d') : 'No due date' }}</span>
                                        <x-badge :value="$task->status->value" type="status" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-panel>

            </div>

        </div>
    </div>
</x-app-layout>
