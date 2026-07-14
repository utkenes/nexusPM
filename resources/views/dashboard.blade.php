<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Workspace Dashboard') }} ({{ $organization->name }})
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + New Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Card: Projects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['projects_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500">Active Projects</div>
                    </div>
                </div>

                <!-- Card: Total Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-sky-50 text-sky-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['tasks_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500">My Assigned Tasks</div>
                    </div>
                </div>

                <!-- Card: Completed Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['completed_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500">Completed Tasks</div>
                    </div>
                </div>

                <!-- Card: Pending Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-amber-50 text-amber-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['pending_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500">Pending Tasks</div>
                    </div>
                </div>
            </div>

            <!-- Two-Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Projects List (2/3 span) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Active Projects</h3>
                        @if($projects->isEmpty())
                            <p class="text-gray-500">No active projects found. Let's create one!</p>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach($projects as $proj)
                                    <div class="py-4 flex justify-between items-center">
                                        <div>
                                            <a href="{{ route('projects.show', $proj) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-lg">
                                                {{ $proj->title }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ Str::limit($proj->description, 100) }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                                            {{ str_replace('_', ' ', $proj->status->value) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Assigned Tasks (1/3 span) -->
                <div class="space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">My Assigned Tasks</h3>
                        @if($assignedTasks->isEmpty())
                            <p class="text-gray-500">No tasks assigned to you in this workspace.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($assignedTasks as $task)
                                    <div class="p-4 border border-gray-100 rounded-lg space-y-2 hover:shadow-sm transition-shadow">
                                        <div class="flex justify-between items-start">
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                {{ $task->project->title }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium @if($task->priority->value === 'high') bg-red-100 text-red-800 @elseif($task->priority->value === 'medium') bg-amber-100 text-amber-800 @else bg-gray-100 text-gray-800 @endif capitalize">
                                                {{ $task->priority->value }}
                                            </span>
                                        </div>
                                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                        <div class="flex justify-between items-center text-xs text-gray-500">
                                            <span>Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</span>
                                            <span class="capitalize bg-gray-150 px-2 py-0.5 rounded border border-gray-200">
                                                {{ str_replace('_', ' ', $task->status->value) }}
                                            </span>
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
