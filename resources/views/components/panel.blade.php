@props(['title' => null, 'padding' => true])

<x-card :title="$title" :padding="$padding" {{ $attributes }}>
    @if(isset($actions))
        <x-slot name="headerAction">
            {{ $actions }}
        </x-slot>
    @endif
    {{ $slot }}
</x-card>
