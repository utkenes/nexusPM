@props(['value', 'type' => 'priority'])

@php
    $normalized = strtolower(trim($value));
    
    if ($type === 'priority') {
        $classes = [
            'high' => 'bg-red-50 text-red-700 border-red-100',
            'medium' => 'bg-amber-50 text-amber-700 border-amber-100',
            'low' => 'bg-slate-50 text-slate-700 border-slate-100',
        ][$normalized] ?? 'bg-gray-50 text-gray-700 border-gray-150';
    } else {
        $classes = [
            'todo' => 'bg-slate-100 text-slate-700 border-slate-200',
            'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
            'review' => 'bg-amber-50 text-amber-700 border-amber-100',
            'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        ][$normalized] ?? 'bg-gray-50 text-gray-700 border-gray-150';
    }
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border capitalize tracking-wider {{ $classes }}">
    {{ str_replace('_', ' ', $value) }}
</span>
