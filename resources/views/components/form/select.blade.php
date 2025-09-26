@props([
    'label' => null,
    'name',
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'selected' => null,
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->merge(['class' => 'shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) }}
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        @if($required) required @endif
    >
        @if($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $labelOption)
            <option value="{{ $value }}" @selected($selected == $value)>{{ $labelOption }}</option>
        @endforeach
    </select>

    <x-form.error name="{{ $name }}" />
</div>

