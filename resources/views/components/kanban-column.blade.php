@props(['status', 'title', 'tasks', 'color' => 'indigo'])

@php
    $columnTasks = $tasks->where('status.value', $status);
    $count = $columnTasks->count();
    
    // Choose Tailwind color schemes dynamically for Dark theme stripes
    $stripeColor = [
        'todo' => 'bg-gray-500',
        'in_progress' => 'bg-orange-500',
        'review' => 'bg-amber-500',
        'done' => 'bg-emerald-500',
    ][$status] ?? 'bg-gray-500';

    $bgHeader = [
        'todo' => 'bg-gray-800 text-gray-300 border-gray-700',
        'in_progress' => 'bg-orange-950/20 text-orange-400 border-orange-900/50',
        'review' => 'bg-amber-950/20 text-amber-400 border-amber-900/50',
        'done' => 'bg-emerald-950/20 text-emerald-400 border-emerald-900/50',
    ][$status] ?? 'bg-gray-800 text-gray-355 border-gray-700';
@endphp

<div 
    class="flex-shrink-0 w-80 bg-gray-900 border border-gray-800/80 rounded-2xl flex flex-col max-h-[80vh] snap-center overflow-hidden shadow-lg shadow-orange-500/[0.01]"
    aria-label="Kanban Column: {{ $title }}"
>
    <!-- Top colored stripe -->
    <div class="h-1.5 w-full {{ $stripeColor }}"></div>

    <div class="p-4 flex flex-col flex-grow">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-2.5">
                <span class="h-2 w-2 rounded-full {{ $stripeColor }}"></span>
                <h3 class="font-bold text-gray-200 text-sm tracking-wide">{{ $title }}</h3>
            </div>
            <span class="inline-flex items-center justify-center h-5 px-2.5 text-[10px] font-black rounded-full {{ $bgHeader }} border">
                {{ $count }}
            </span>
        </div>

        <!-- Drop Zone / Cards List -->
        <div 
            id="col-{{ $status }}" 
            class="space-y-3 overflow-y-auto flex-grow pb-8 pr-1 min-h-[300px] scrollbar-thin transition-colors duration-150 rounded-xl"
            ondragover="allowDrop(event)" 
            ondrop="drop(event, '{{ $status }}')"
        >
            @if($count === 0)
                <div class="h-full flex items-center justify-center p-6 border-2 border-dashed border-gray-800 rounded-xl text-center mt-2 bg-gray-950/20">
                    <div>
                        <svg class="mx-auto h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        <p class="text-[10px] font-bold text-gray-550 uppercase tracking-widest mt-2">Drop tasks here</p>
                    </div>
                </div>
            @else
                @foreach($columnTasks as $task)
                    <x-task-card :task="$task" />
                @endforeach
            @endif
        </div>
    </div>
</div>
