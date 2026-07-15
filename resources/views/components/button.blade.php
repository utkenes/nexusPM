@props(['variant' => 'primary', 'type' => 'button'])

@php
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 border rounded-lg font-bold text-xs uppercase tracking-widest transition duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-950 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer shadow-sm';
    
    $variants = [
        'primary' => 'bg-orange-600 border-transparent text-white hover:bg-orange-700 active:bg-orange-950 focus:ring-orange-500/30 hover:shadow-lg hover:shadow-orange-500/20',
        'secondary' => 'bg-gray-900 border-gray-800 text-gray-300 hover:bg-gray-850 hover:border-gray-700 active:bg-gray-950 focus:ring-gray-800',
        'danger' => 'bg-red-600 border-transparent text-white hover:bg-red-700 active:bg-red-950 focus:ring-red-500/30',
        'success' => 'bg-emerald-600 border-transparent text-white hover:bg-emerald-700 active:bg-emerald-950 focus:ring-emerald-500/30',
        'outline' => 'bg-transparent border-gray-800 text-gray-300 hover:bg-gray-900 hover:border-gray-750 active:bg-gray-950 focus:ring-orange-500/20',
        'ghost' => 'bg-transparent border-transparent text-gray-400 hover:bg-gray-900 hover:text-gray-200 active:bg-gray-950 focus:ring-orange-500/10 shadow-none',
    ];

    $class = $variants[$variant] ?? $variants['primary'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
    {{ $slot }}
</button>
