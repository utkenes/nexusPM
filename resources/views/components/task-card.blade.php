@props(['task'])

@php
    $checklistCount = $task->checklistItems->count();
    $completedChecklistCount = $task->checklistItems->where('is_completed', true)->count();
    $commentsCount = $task->comments->count();
    $attachmentsCount = $task->attachments->count();
@endphp

<div 
    id="task-{{ $task->id }}" 
    data-task-id="{{ $task->id }}"
    data-title="{{ strtolower($task->title) }}"
    data-description="{{ strtolower($task->description ?? '') }}"
    data-priority="{{ $task->priority->value }}"
    data-assignee="{{ $task->assigned_to ?? '' }}"
    data-status="{{ $task->status->value }}"
    draggable="true" 
    ondragstart="drag(event)" 
    @click="$store.taskDrawer.openDrawer({{ $task->id }}, $el)"
    @keydown.enter="$store.taskDrawer.openDrawer({{ $task->id }}, $el)"
    @keydown.space.prevent="$store.taskDrawer.openDrawer({{ $task->id }}, $el)"
    x-show="matchesFilters($el)"
    tabindex="0"
    class="bg-gray-900 p-4 rounded-xl shadow-sm border border-gray-800/80 cursor-grab active:cursor-grabbing hover:shadow-md hover:border-orange-500/40 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all space-y-3"
    role="button"
    aria-label="Task Card: {{ $task->title }}"
>
    <!-- Priority Badge & Assignee Avatar -->
    <div class="flex justify-between items-center">
        <x-badge :value="$task->priority->value" type="priority" />
        @if($task->assignee)
            <x-avatar :name="$task->assignee->name" size="xs" />
        @endif
    </div>

    <!-- Title & Description Summary -->
    <div>
        <h4 class="font-bold text-gray-200 text-sm leading-snug tracking-tight">{{ $task->title }}</h4>
        @if($task->description)
            <p class="text-xs text-gray-400 mt-1 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
        @endif
    </div>

    <!-- Label Pills -->
    @if($task->labels->isNotEmpty())
        <div class="flex flex-wrap gap-1.5 pt-1">
            @foreach($task->labels as $lbl)
                <span 
                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border"
                    style="background-color: {{ $lbl->color }}20; border-color: {{ $lbl->color }}40; color: {{ $lbl->color }};"
                >
                    {{ $lbl->name }}
                </span>
            @endforeach
        </div>
    @endif

    <!-- Progress Indicator -->
    @if($checklistCount > 0)
        <div class="space-y-1">
            <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                <span>Checklist Progress</span>
                <span>{{ $completedChecklistCount }}/{{ $checklistCount }}</span>
            </div>
            <x-progress-bar :value="$completedChecklistCount" :max="$checklistCount" />
        </div>
    @endif

    <!-- Card Metadata: Comments, Attachments & Due Date -->
    <div class="flex justify-between items-center pt-2 border-t border-gray-850 text-[11px] text-gray-500">
        <div class="flex items-center space-x-3">
            <!-- Comments Count -->
            @if($commentsCount > 0)
                <span class="flex items-center space-x-1" title="{{ $commentsCount }} comments">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>{{ $commentsCount }}</span>
                </span>
            @endif

            <!-- Attachments Count -->
            @if($attachmentsCount > 0)
                <span class="flex items-center space-x-1" title="{{ $attachmentsCount }} attachments">
                    <svg class="h-3.5 w-3.5 text-gray-555" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span>{{ $attachmentsCount }}</span>
                </span>
            @endif
        </div>

        <!-- Due Date -->
        @if($task->due_date)
            <span class="flex items-center space-x-1 font-semibold text-gray-400" title="Due Date">
                <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $task->due_date->format('M d') }}</span>
            </span>
        @endif
    </div>
</div>
