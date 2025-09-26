@props(['paginator', 'class' => ''])

@php
    $baseLinkClasses = 'inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    $linkClasses = 'text-gray-700 bg-transparent border border-gray-300 hover:bg-gray-100 focus-visible:outline-gray-400';
    $activeClasses = 'bg-gray-700 text-white hover:bg-gray-900 focus-visible:outline-gray-800';
@endphp

@if($paginator->hasPages())
    <div {{ $attributes->class(['flex items-center justify-center gap-2', $class]) }}>
        @if($paginator->onFirstPage())
            <span class="{{ $baseLinkClasses }} {{ $linkClasses }} cursor-not-allowed opacity-50"><</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="{{ $baseLinkClasses }} {{ $linkClasses }}"><</a>
        @endif

        <div class="flex items-center gap-1">
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="{{ $baseLinkClasses }} {{ $activeClasses }} w-10 h-10">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="{{ $baseLinkClasses }} {{ $linkClasses }} w-10 h-10">{{ $page }}</a>
                @endif
            @endforeach
        </div>

        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="{{ $baseLinkClasses }} {{ $linkClasses }}">></a>
        @else
            <span class="{{ $baseLinkClasses }} {{ $linkClasses }} cursor-not-allowed opacity-50">></span>
        @endif
    </div>
@endif
