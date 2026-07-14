@props(['title', 'message', 'actionUrl' => null, 'actionText' => null, 'icon' => 'folder'])

<div class="text-center py-12 px-6 border border-gray-150 rounded-xl bg-white shadow-sm flex flex-col items-center">
    <div class="p-4 rounded-full bg-indigo-50 text-indigo-500 mb-4">
        @if($icon === 'folder')
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
        @elseif($icon === 'tasks')
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
        @else
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        @endif
    </div>
    
    <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ $title }}</h3>
    <p class="text-sm text-gray-500 max-w-sm mt-1 mb-6 leading-relaxed">{{ $message }}</p>
    
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
            {{ $actionText }}
        </a>
    @endif
</div>
