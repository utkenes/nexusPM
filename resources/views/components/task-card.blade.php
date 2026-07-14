@props(['task'])

<div 
    id="task-{{ $task->id }}" 
    data-task-id="{{ $task->id }}"
    draggable="true" 
    ondragstart="drag(event)" 
    onclick="openTaskDetailModal({{ $task->id }})"
    class="bg-white p-4 rounded-lg shadow-sm border border-gray-150 cursor-grab active:cursor-grabbing hover:shadow-md transition-shadow space-y-3"
>
    <!-- Priority Badge & Options -->
    <div class="flex justify-between items-center">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold @if($task->priority->value === 'high') bg-red-50 text-red-700 @elseif($task->priority->value === 'medium') bg-amber-50 text-amber-700 @else bg-gray-50 text-gray-700 @endif capitalize">
            {{ $task->priority->value }}
        </span>
        @if($task->assignee)
            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded-full" title="{{ $task->assignee->name }}">
                {{ Str::limit($task->assignee->name, 10, '') }}
            </span>
        @endif
    </div>

    <!-- Title & Description -->
    <div>
        <h4 class="font-bold text-gray-900 text-sm leading-snug">{{ $task->title }}</h4>
        @if($task->description)
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
        @endif
    </div>

    <!-- Progress & Due Date -->
    <div class="flex justify-between items-center pt-2 border-t border-gray-50 text-[11px] text-gray-400">
        <!-- Checklist Progress -->
        @if($task->checklistItems->isNotEmpty())
            <span class="flex items-center space-x-1">
                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
                <span>
                    {{ $task->checklistItems->where('is_completed', true)->count() }}/{{ $task->checklistItems->count() }}
                </span>
            </span>
        @else
            <span></span>
        @endif

        <!-- Due Date -->
        @if($task->due_date)
            <span class="flex items-center space-x-1">
                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $task->due_date->format('M d') }}</span>
            </span>
        @endif
    </div>
</div>
