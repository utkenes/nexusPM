@props(['options' => [], 'label' => 'Filter', 'model' => ''])

<div class="flex items-center space-x-2">
    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $label }}</label>
    <select 
        x-model="{{ $model }}"
        class="border border-gray-300 rounded-lg text-sm py-1.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white transition duration-150"
    >
        <option value="">All</option>
        @foreach($options as $val => $name)
            <option value="{{ $val }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
