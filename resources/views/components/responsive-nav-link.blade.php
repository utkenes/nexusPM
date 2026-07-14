@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-orange-500 text-start text-base font-semibold text-orange-500 bg-orange-950/20 focus:outline-none focus:text-orange-400 focus:border-orange-600 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-gray-250 hover:bg-gray-900 hover:border-gray-700 focus:outline-none focus:text-gray-200 focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
