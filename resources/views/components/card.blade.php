@props([
    'title' => null,
    'actions' => null,
    'padding' => 'p-6',
    'maxWidth' => null,
])

<div {{ $attributes->class(["bg-white shadow-md rounded-lg flex flex-col h-full", $padding, $maxWidth]) }}>
    @if($title || $actions)
        <div class="flex items-start justify-between gap-4 mb-4 flex-shrink-0">
            @if($title)
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
            @endif
            @if($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif

    <div class="flex-1 overflow-auto">
        <div class="space-y-4">
            {{ $slot }}
        </div>
    </div>
</div>

