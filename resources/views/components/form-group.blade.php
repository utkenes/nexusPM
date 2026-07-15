@props(['label' => null, 'error' => null, 'helper' => null, 'required' => false])

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label class="block text-xs font-black uppercase tracking-wider text-gray-400 select-none">
            {{ $label }}
            @if($required)
                <span class="text-orange-500" title="Required">*</span>
            @endif
        </label>
    @endif

    <div>
        {{ $slot }}
    </div>

    @if($helper)
        <p class="text-[10px] text-gray-550 font-semibold leading-normal mt-1">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="text-xs text-red-500 font-bold mt-1.5 flex items-center space-x-1">
            <span class="inline-block h-1 w-1 rounded-full bg-red-500"></span>
            <span>{{ $error }}</span>
        </p>
    @endif
</div>
