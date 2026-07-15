@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if($title || $description)
        <div class="border-b border-gray-850 pb-4">
            @if($title)
                <h2 class="text-lg font-black text-gray-100 tracking-tight leading-tight">{{ $title }}</h2>
            @endif
            @if($description)
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
