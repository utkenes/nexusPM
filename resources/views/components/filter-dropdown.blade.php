@props(['options' => [], 'label' => 'Filter', 'model' => ''])

<div class="flex items-center space-x-2">
    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $label }}</label>
    <select 
        x-model="{{ $model }}"
        class="border border-gray-800 rounded-lg text-xs py-1.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-900 text-gray-300 transition duration-150"
    >
        <option value="" class="bg-gray-900">All</option>
        @foreach($options as $val => $name)
            <option value="{{ $val }}" class="bg-gray-900">{{ $name }}</option>
        @endforeach
    </select>
</div>
