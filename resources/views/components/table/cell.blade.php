@props(['header' => false])

@php
    $baseClass = 'border border-2 border-gray-300 px-3 py-2';
    $class = $header ? $baseClass . ' font-semibold text-gray-700 bg-gray-50' : $baseClass . ' text-gray-800';
@endphp

<td {{ $attributes->class($class) }}>
    {{ $slot }}
</td>

