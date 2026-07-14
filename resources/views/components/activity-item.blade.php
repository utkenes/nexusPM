@props(['activity', 'showLine' => true])

<div class="relative pb-6 last:pb-0">
    @if($showLine)
        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-800" aria-hidden="true"></span>
    @endif
    <div class="relative flex space-x-3">
        <div>
            <span class="h-8 w-8 rounded-full bg-gray-950 border border-gray-800 flex items-center justify-center text-xs text-orange-500">
                ⏱️
            </span>
        </div>
        <div class="flex-grow min-w-0 pt-1.5 flex justify-between space-x-4">
            <div>
                <p class="text-xs text-gray-400">
                    <span class="font-bold text-gray-250">{{ $activity->causer ? $activity->causer->name : 'System' }}</span>
                    <span>{{ $activity->description }}</span>
                    @if($activity->subject)
                        <span class="font-semibold text-orange-500">"{{ $activity->subject->title ?? $activity->subject->name ?? 'Resource' }}"</span>
                    @endif
                </p>
            </div>
            <div class="text-right text-[10px] whitespace-nowrap text-gray-550">
                <span>{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>
