@props(['project'])

@php
    $totalTasks = $project->tasks()->count();
    $completedTasks = $project->tasks()->where('status', \App\Enums\TaskStatus::Done)->count();
    $percent = $totalTasks > 0 ? min(100, round(($completedTasks / $totalTasks) * 100)) : 0;
@endphp

<div class="p-6 bg-gray-900 border border-gray-800 rounded-2xl shadow-lg hover:shadow-orange-500/[0.04] hover:border-orange-500/40 hover:-translate-y-0.5 transition duration-200 flex flex-col justify-between space-y-4">
    <div class="space-y-3">
        <!-- Title & Status -->
        <div class="flex justify-between items-start">
            <h4 class="text-base font-bold text-gray-150 leading-snug tracking-tight">
                <a href="{{ route('projects.show', $project) }}" class="hover:text-orange-500 transition-colors">
                    {{ $project->title }}
                </a>
            </h4>
            <x-badge :value="$project->status->value" type="status" />
        </div>

        <!-- Description -->
        <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">{{ $project->description ?? 'No description provided.' }}</p>

        <!-- Progress -->
        <div class="space-y-1.5 pt-2">
            <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                <span>Progress</span>
                <span>{{ $percent }}%</span>
            </div>
            <x-progress-bar :value="$completedTasks" :max="$totalTasks" />
        </div>
    </div>

    <!-- Footer meta -->
    <div class="border-t border-gray-850 pt-4 flex justify-between items-center text-[11px] text-gray-400">
        <span class="flex items-center space-x-1.5">
            <x-avatar :name="$project->creator->name" size="xs" />
            <span class="text-gray-400">{{ $project->creator->name }}</span>
        </span>
        <div class="flex items-center space-x-3">
            <span class="text-[10px] font-semibold text-gray-500 uppercase">{{ $completedTasks }}/{{ $totalTasks }} Tasks</span>
            <a href="{{ route('projects.edit', $project) }}" class="text-orange-500 hover:text-orange-400 font-bold">Edit</a>
        </div>
    </div>
</div>
