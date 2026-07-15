@props(['title' => null, 'padding' => true, 'headerAction' => null])

<div {{ $attributes->merge(['class' => 'bg-gray-900 border border-gray-800 rounded-xl shadow-sm hover:border-orange-500/20 transition duration-200 overflow-hidden']) }}>
    @if($title || $headerAction)
        <div class="px-6 py-4 border-b border-gray-850 flex justify-between items-center bg-gray-900/40">
            @if($title)
                <h3 class="text-xs font-black text-gray-200 uppercase tracking-widest">{{ $title }}</h3>
            @endif
            @if($headerAction)
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif
    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
