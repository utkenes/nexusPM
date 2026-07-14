@props(['value', 'max' => 100])

@php
    $percent = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;
    
    $barColor = 'bg-indigo-600';
    if ($percent === 100) {
        $barColor = 'bg-emerald-600';
    } elseif ($percent < 30) {
        $barColor = 'bg-rose-500';
    }
@endphp

<div class="w-full flex items-center space-x-2">
    <div class="flex-grow bg-gray-150 h-2 rounded-full overflow-hidden">
        <div 
            class="h-full rounded-full transition-all duration-300 {{ $barColor }}" 
            style="width: {{ $percent }}%"
            role="progressbar"
            aria-valuenow="{{ $percent }}"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
    <span class="text-xs font-bold text-gray-500 min-w-[32px] text-right">{{ $percent }}%</span>
</div>
