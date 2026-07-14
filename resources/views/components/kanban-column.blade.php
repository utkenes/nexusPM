@props(['status', 'title', 'tasks', 'color' => 'indigo'])

@php
    $columnTasks = $tasks->where('status.value', $status);
    $count = $columnTasks->count();
    
    // Choose Tailwind color schemes dynamically for Dark theme
    $bgHeader = [
        'todo' => 'bg-gray-800 text-gray-300 border-gray-700',
        'in_progress' => 'bg-orange-950/20 text-orange-400 border-orange-900/50',
        'review' => 'bg-amber-950/20 text-amber-400 border-amber-900/50',
        'done' => 'bg-emerald-950/20 text-emerald-400 border-emerald-900/50',
    ][$status] ?? 'bg-gray-800 text-gray-350 border-gray-700';

    $dotColor = [
        'todo' => 'bg-gray-500',
        'in_progress' => 'bg-orange-500',
        'review' => 'bg-amber-500',
        'done' => 'bg-emerald-550',
    ][$status] ?? 'bg-gray-500';
@endphp

<div 
    class="flex-shrink-0 w-80 bg-gray-900/40 rounded-xl border border-gray-800/80 p-4 flex flex-col max-h-[80vh] snap-center select-none"
    aria-label="Kanban Column: {{ $title }}"
>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
            <h3 class="font-bold text-gray-250 text-sm tracking-wide">{{ $title }}</h3>
        </div>
        <span class="inline-flex items-center justify-center h-5 px-2 text-xs font-semibold rounded-full {{ $bgHeader }} border">
            {{ $count }}
        </span>
    </div>

    <!-- Drop Zone / Cards List -->
    <div 
        id="col-{{ $status }}" 
        class="space-y-3 overflow-y-auto flex-grow pb-8 pr-1 min-h-[300px] scrollbar-thin transition-colors duration-150 rounded-lg"
        ondragover="allowDrop(event)" 
        ondrop="drop(event, '{{ $status }}')"
    >
        @if($count === 0)
            <div class="h-full flex items-center justify-center p-6 border-2 border-dashed border-gray-800/80 rounded-xl text-center mt-2 bg-gray-900/10">
                <div>
                    <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs font-medium text-gray-550 mt-2">Drop tasks here</p>
                </div>
            </div>
        @else
            @foreach($columnTasks as $task)
                <x-task-card :task="$task" />
            @endforeach
        @endif
    </div>
</div>
