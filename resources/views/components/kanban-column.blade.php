@props(['status', 'title', 'tasks', 'color' => 'indigo'])

@php
    $columnTasks = $tasks->where('status.value', $status);
    $count = $columnTasks->count();
    
    // Choose Tailwind color schemes dynamically
    $bgHeader = [
        'todo' => 'bg-gray-100 text-gray-800 border-gray-200',
        'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'review' => 'bg-amber-50 text-amber-700 border-amber-100',
        'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
    ][$status] ?? 'bg-gray-50 text-gray-700 border-gray-200';

    $dotColor = [
        'todo' => 'bg-gray-400',
        'in_progress' => 'bg-indigo-600',
        'review' => 'bg-amber-500',
        'done' => 'bg-emerald-500',
    ][$status] ?? 'bg-gray-400';
@endphp

<div 
    class="flex-shrink-0 w-80 bg-gray-50 rounded-xl border border-gray-200/80 p-4 flex flex-col max-h-[80vh] snap-center select-none"
    aria-label="Kanban Column: {{ $title }}"
>
    <!-- Header -->
    <div class="flex justify-between items-center mb-3">
        <div class="flex items-center space-x-2">
            <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
            <h3 class="font-bold text-gray-800 text-sm tracking-wide">{{ $title }}</h3>
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
            <div class="h-full flex items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-lg text-center mt-2">
                <div>
                    <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs font-medium text-gray-400 mt-2">Drop tasks here</p>
                </div>
            </div>
        @else
            @foreach($columnTasks as $task)
                <x-task-card :task="$task" />
            @endforeach
        @endif
    </div>
</div>
