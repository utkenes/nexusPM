@props(['label', 'value'])

<div class="flex justify-between items-center py-2 text-xs border-b border-gray-850 last:border-0 last:pb-0">
    <span class="text-gray-500 font-semibold uppercase tracking-wider text-[10px]">{{ $label }}</span>
    <span class="text-gray-250 font-bold text-right">{{ $value }}</span>
</div>
