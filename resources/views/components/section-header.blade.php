@props(['title', 'description' => null])

<div class="flex justify-between items-center pb-5 border-b border-gray-800 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-100 tracking-tight leading-none">{{ $title }}</h2>
        @if($description)
            <p class="text-xs text-gray-500 mt-1.5 font-medium">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center space-x-2">
            {{ $actions }}
        </div>
    @endif
</div>
