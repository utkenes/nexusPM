@props(['href' => '#', 'onclick' => null])

<a 
    href="{{ $href }}" 
    @if($onclick) onclick="{{ $onclick }}" @endif
    class="block px-4 py-2 text-xs font-semibold text-gray-400 hover:text-gray-200 hover:bg-gray-800 transition-colors"
>
    {{ $slot }}
</a>
