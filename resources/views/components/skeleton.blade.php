@props(['type' => 'card', 'count' => 1])

@if($type === 'card')
    <div class="space-y-4">
        @for($i = 0; $i < $count; $i++)
            <div class="animate-pulse bg-white p-4 rounded-lg border border-gray-100 space-y-3">
                <div class="flex justify-between items-center">
                    <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                    <div class="h-4 bg-gray-200 rounded-full w-8"></div>
                </div>
                <div class="h-5 bg-gray-200 rounded w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded w-full"></div>
                <div class="pt-2 flex justify-between">
                    <div class="h-3 bg-gray-200 rounded w-8"></div>
                    <div class="h-3 bg-gray-200 rounded w-12"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'list')
    <div class="space-y-3">
        @for($i = 0; $i < $count; $i++)
            <div class="animate-pulse flex items-center space-x-3 py-2 border-b border-gray-50">
                <div class="h-4 w-4 bg-gray-200 rounded"></div>
                <div class="h-4 bg-gray-200 rounded flex-grow"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'drawer')
    <div class="animate-pulse space-y-6">
        <div class="space-y-2">
            <div class="h-3 bg-gray-200 rounded w-16"></div>
            <div class="h-6 bg-gray-200 rounded w-3/4"></div>
        </div>
        <div class="border-t border-b border-gray-100 py-4 grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <div class="h-3 bg-gray-200 rounded w-12"></div>
                <div class="h-4 bg-gray-200 rounded w-20"></div>
            </div>
            <div class="space-y-2">
                <div class="h-3 bg-gray-200 rounded w-12"></div>
                <div class="h-4 bg-gray-200 rounded w-20"></div>
            </div>
        </div>
        <div class="space-y-3">
            <div class="h-4 bg-gray-200 rounded w-24"></div>
            <div class="h-3 bg-gray-200 rounded w-full"></div>
            <div class="h-3 bg-gray-200 rounded w-5/6"></div>
        </div>
    </div>
@endif
