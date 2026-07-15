@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge(['class' => 'block w-full bg-gray-955 border border-gray-800 text-gray-100 placeholder:text-gray-500 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm caret-orange-500 selection:bg-orange-500/30 text-sm py-2 px-3.5 disabled:opacity-50 disabled:cursor-not-allowed']) }}
>
