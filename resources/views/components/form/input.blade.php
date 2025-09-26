@props([
    'type' => 'text',
    'name',
    'required' => false,
    'value' => null,
    'placeholder' => null,
    'label' => null,
    'noAsterisk' => false,
])

<div class="space-y-1">
    @if($label)
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>
    @endif

    <input
        {{ $attributes->merge(['class' => 'shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) }}
        type="{{ $type }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        value="{{ old(htmlFormNotationToDot($name)) ?? $value ?? $attributes->get('value') }}"
        @if($required) required @endif
        placeholder="{{ $placeholder }}{{ $required && !$noAsterisk ? '*' : '' }}"
    >

    <x-form.error name="{{ $name }}" />
</div>

