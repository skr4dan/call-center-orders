@props([ 'variant' => 'primary', 'type' => 'button', 'href' => null ])

@php
    $base = 'inline-flex items-center justify-center rounded px-4 py-2 text-sm font-medium transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2';
    $variants = [
        'primary' => 'bg-gray-800 text-white hover:bg-gray-900 focus-visible:outline-gray-800',
        'primary-outline' => 'bg-transparent text-gray-900 border border-gray-800 hover:bg-gray-100 focus-visible:outline-gray-800',
        'secondary' => 'bg-gray-300 text-gray-900 hover:bg-gray-400 focus-visible:outline-gray-400',
        'secondary-outline' => 'bg-transparent text-gray-900 border border-gray-300 hover:bg-gray-100 focus-visible:outline-gray-200',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-200',
    ];
    $class = $variants[$variant] ?? $variants['primary'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$base, $class]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$base, $class]) }}>
        {{ $slot }}
    </button>
@endif
