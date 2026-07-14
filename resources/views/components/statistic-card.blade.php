@props(['title', 'value', 'icon', 'color' => 'indigo'])

@php
    $bgColor = [
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'sky' => 'bg-sky-50 text-sky-600',
    ][$color] ?? 'bg-gray-50 text-gray-600';
@endphp

<div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-150 p-6 flex items-center space-x-4 hover:shadow transition-shadow">
    <div class="p-3.5 rounded-xl {{ $bgColor }}">
        {!! $icon !!}
    </div>
    <div>
        <div class="text-2xl font-bold text-gray-900 tracking-tight">{{ $value }}</div>
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-0.5">{{ $title }}</div>
    </div>
</div>
