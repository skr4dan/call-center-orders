@props([
    'headers' => [],
])

<div class="overflow-auto">
    <table {{ $attributes->class('w-full text-sm [&>tbody>tr:nth-child(odd)]:bg-stone-200') }}>
        @if($headers)
            <thead>
                <tr class="text-lg">
                    @foreach($headers as $header)
                        <th class="border-2 border-gray-300 px-5 py-3 text-center font-semibold text-gray-700">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

