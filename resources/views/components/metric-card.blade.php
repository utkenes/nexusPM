@props(['title', 'value', 'icon' => null, 'trend' => null])

<div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-lg shadow-orange-500/[0.02] flex items-center justify-between hover:border-gray-700 transition duration-150">
    <div class="space-y-1">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest">{{ $title }}</span>
        <div class="flex items-baseline space-x-2">
            <span class="text-3xl font-black text-gray-100 tracking-tight">{{ $value }}</span>
            @if($trend)
                <span class="text-xs font-bold text-orange-500">{{ $trend }}</span>
            @endif
        </div>
    </div>
    @if($icon)
        <div class="p-3 bg-gray-950 rounded-xl border border-gray-800 text-orange-500">
            {!! $icon !!}
        </div>
    @endif
</div>
