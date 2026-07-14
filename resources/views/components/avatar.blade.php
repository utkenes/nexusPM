@props(['name', 'size' => 'sm'])

@php
    $words = explode(' ', trim($name));
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(substr($name, 0, 2));
    }

    // Dynamic background color based on name string sum
    $colors = ['bg-indigo-600', 'bg-emerald-600', 'bg-sky-600', 'bg-amber-600', 'bg-purple-600', 'bg-rose-600'];
    $charSum = array_sum(array_map('ord', str_split($name)));
    $bgClass = $colors[$charSum % count($colors)];

    $sizeClasses = [
        'xs' => 'h-5 w-5 text-[10px]',
        'sm' => 'h-7 w-7 text-xs',
        'md' => 'h-9 w-9 text-sm',
        'lg' => 'h-12 w-12 text-lg font-bold',
    ][$size] ?? 'h-8 w-8 text-xs';
@endphp

<div 
    class="inline-flex items-center justify-center rounded-full text-white font-semibold shadow-sm {{ $bgClass }} {{ $sizeClasses }}" 
    title="{{ $name }}"
    role="img"
    aria-label="Avatar of {{ $name }}"
>
    {{ $initials }}
</div>
