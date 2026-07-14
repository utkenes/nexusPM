@props(['type' => 'button', 'ariaLabel' => 'Action'])

<button 
    type="{{ $type }}" 
    class="p-2 rounded-lg bg-gray-950 border border-gray-800 text-gray-400 hover:text-gray-250 hover:bg-gray-850 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition-all shadow-sm"
    aria-label="{{ $ariaLabel }}"
    {{ $attributes }}
>
    {{ $slot }}
</button>
