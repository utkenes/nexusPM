<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Task Calendar" 
            description="Overview of your workspace tasks mapped to their due dates."
        >
            <x-slot name="actions">
                <div class="flex items-center space-x-2 bg-gray-900 p-1.5 rounded-xl border border-gray-800 shadow-sm">
                    <!-- Previous Month -->
                    <a 
                        href="{{ route('calendar.index', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                        class="p-2 bg-gray-950 hover:bg-gray-850 border border-gray-800 rounded-lg text-gray-400 hover:text-gray-250 transition-colors"
                        aria-label="Previous Month"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <!-- Current Month Text -->
                    <span class="text-xs font-black text-gray-200 px-3 uppercase tracking-wider">
                        {{ $currentMonthName }}
                    </span>

                    <!-- Next Month -->
                    <a 
                        href="{{ route('calendar.index', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                        class="p-2 bg-gray-950 hover:bg-gray-850 border border-gray-800 rounded-lg text-gray-400 hover:text-gray-250 transition-colors"
                        aria-label="Next Month"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </x-slot>
        </x-section-header>
    </x-slot>

    <div class="py-8 bg-gray-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Calendar Grid Container -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800/80 shadow-xl overflow-hidden">
                <!-- Day Names Header -->
                <div class="grid grid-cols-7 bg-gray-900/60 border-b border-gray-800 text-center py-3.5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div class="text-orange-500/80">Sat</div>
                    <div class="text-orange-500/80">Sun</div>
                </div>

                <!-- Calendar Cells -->
                <div class="grid grid-cols-7 bg-gray-950">
                    <!-- Leading empty days -->
                    @for($i = 1; $i < $startOfWeekDay; $i++)
                        <div class="bg-gray-900/15 min-h-[130px] border-b border-r border-gray-850/50 p-2"></div>
                    @endfor

                    <!-- Active Days -->
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $dayTasks = $tasksByDay->get($dateStr, collect());
                            $isToday = $dateStr === date('Y-m-d');
                        @endphp
                        
                        <div class="min-h-[130px] bg-gray-900 border-b border-r border-gray-850/60 p-2.5 flex flex-col justify-between hover:bg-gray-850/20 transition-colors {{ $isToday ? 'ring-1 ring-inset ring-orange-500/30 bg-orange-950/5' : '' }}">
                            <!-- Day Number -->
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs font-black {{ $isToday ? 'text-orange-500' : 'text-gray-500' }}">
                                    {{ $day }}
                                </span>
                                @if($isToday)
                                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-orange-500" title="Today"></span>
                                @endif
                            </div>

                            <!-- Tasks List inside Day -->
                            <div class="flex-grow space-y-1.5 overflow-y-auto max-h-[85px] scrollbar-thin">
                                @foreach($dayTasks as $task)
                                    @php
                                        // Priority styles mapping
                                        $priorityClass = 'bg-gray-850 text-gray-400 border-gray-800';
                                        if ($task->priority->value === 'high') {
                                            $priorityClass = 'bg-red-500/10 text-red-400 border-red-500/20';
                                        } elseif ($task->priority->value === 'medium') {
                                            $priorityClass = 'bg-orange-500/10 text-orange-400 border-orange-500/20';
                                        }
                                    @endphp
                                    <div 
                                        @click="$store.taskDrawer.openDrawer({{ $task->id }}, null)"
                                        class="text-[10px] font-bold px-2 py-1 border rounded-lg cursor-pointer truncate hover:scale-[1.02] active:scale-[0.98] transition-all {{ $priorityClass }}"
                                        title="{{ $task->title }}"
                                        role="button"
                                        aria-label="View task details for {{ $task->title }}"
                                    >
                                        {{ $task->title }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    <!-- Trailing empty days -->
                    @for($i = $endOfWeekDay; $i < 7; $i++)
                        <div class="bg-gray-900/15 min-h-[130px] border-b border-r border-gray-850/50 p-2"></div>
                    @endfor
                </div>
            </div>

        </div>
    </div>

    <!-- Reusable Task details slide-over drawer -->
    <x-drawer />
</x-app-layout>
