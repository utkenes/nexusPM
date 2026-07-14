@props(['placeholder' => 'Search tasks...'])

<div class="relative w-full max-w-xs">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <input 
        type="search" 
        x-model="search"
        placeholder="{{ $placeholder }}" 
        class="block w-full pl-9 pr-8 py-2 border border-gray-300 rounded-lg text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
        aria-label="Search"
    >
    <button 
        x-show="search.length > 0" 
        @click="search = ''" 
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-450 hover:text-gray-600"
        style="display: none;"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
