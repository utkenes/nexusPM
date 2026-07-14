@props(['title', 'value', 'icon', 'color' => 'indigo'])

<div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-xl border border-gray-800 p-6 flex items-center space-x-4 hover:shadow-md transition-shadow">
    <div class="p-3.5 rounded-xl bg-orange-950/20 text-orange-500 border border-orange-500/20">
        {!! $icon !!}
    </div>
    <div>
        <div class="text-2xl font-bold text-gray-100 tracking-tight">{{ $value }}</div>
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-0.5">{{ $title }}</div>
    </div>
</div>
