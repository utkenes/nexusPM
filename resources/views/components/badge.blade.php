@props(['value', 'type' => 'priority'])

@php
    $normalized = strtolower(trim($value));
    
    if ($type === 'priority') {
        $classes = [
            'high' => 'bg-red-950/30 text-red-400 border-red-900/50',
            'medium' => 'bg-amber-950/30 text-amber-400 border-amber-900/50',
            'low' => 'bg-slate-800 text-slate-300 border-slate-700',
        ][$normalized] ?? 'bg-gray-800 text-gray-300 border-gray-700';
    } else {
        $classes = [
            'todo' => 'bg-slate-800 text-slate-300 border-slate-700',
            'in_progress' => 'bg-orange-950/30 text-orange-400 border-orange-900/50',
            'review' => 'bg-amber-950/30 text-amber-400 border-amber-900/50',
            'done' => 'bg-emerald-950/30 text-emerald-400 border-emerald-900/50',
        ][$normalized] ?? 'bg-gray-800 text-gray-300 border-gray-700';
    }
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border capitalize tracking-wider {{ $classes }}">
    {{ str_replace('_', ' ', $value) }}
</span>
