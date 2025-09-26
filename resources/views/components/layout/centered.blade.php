@props(['width' => 'max-w-sm'])

<div {{ $attributes->class('flex items-center justify-center min-h-screen bg-gray-100') }}>
    <div class="w-full {{ $width }}">
        {{ $slot }}
    </div>
</div>

