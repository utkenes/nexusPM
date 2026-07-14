@props(['placeholder' => 'Search...'])

<div class="relative w-full max-w-xs">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <input 
        type="search" 
        x-model="search"
        placeholder="{{ $placeholder }}" 
        class="block w-full pl-9 pr-8 py-2 border border-gray-800 rounded-lg text-sm bg-gray-900 text-gray-200 placeholder-gray-550 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-150"
        aria-label="Search"
    >
    <button 
        x-show="search.length > 0" 
        @click="search = ''" 
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300"
        style="display: none;"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
