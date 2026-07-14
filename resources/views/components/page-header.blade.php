@props(['title', 'description' => null])

<div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 border-b border-gray-800 mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-100 tracking-tight leading-none">{{ $title }}</h1>
        @if($description)
            <p class="text-xs text-gray-400 mt-2 font-medium leading-relaxed max-w-2xl">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center space-x-3 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>
