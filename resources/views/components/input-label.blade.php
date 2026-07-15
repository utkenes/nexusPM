@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-black uppercase tracking-wider text-gray-400 select-none mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
