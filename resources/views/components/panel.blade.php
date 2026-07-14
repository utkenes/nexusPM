@props(['title' => null, 'padding' => true])

<div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg">
    @if($title)
        <div class="px-6 py-4 border-b border-gray-850 flex justify-between items-center bg-gray-900/50">
            <h3 class="text-sm font-bold text-gray-200 tracking-wide uppercase tracking-widest text-xs">{{ $title }}</h3>
            @if(isset($actions))
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
