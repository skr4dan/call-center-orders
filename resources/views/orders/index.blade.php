@extends('layouts.app')

@section('content')
<div class="h-screen bg-gray-100 p-15 flex flex-col">
    <div class="flex-1 bg-white rounded-lg shadow-md flex flex-col min-h-0">
        <div class="flex-shrink-0 p-4">
            <h2 class="text-lg font-semibold mb-4">Фильтры заказов</h2>
            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-3">
                <x-form.input
                    class="flex-1 min-w-[200px] xl:min-w-[450px]"
                    name="search"
                    label="Поиск"
                    placeholder="ФИО, компания, телефон, товар"
                    value="{{ request('search') }}"
                />

                <x-form.input
                    type="date"
                    name="date_from"
                    label="С даты"
                    value="{{ request('date_from') }}"
                />

                <x-form.input
                    type="date"
                    name="date_to"
                    label="По дату"
                    value="{{ request('date_to') }}"
                />

                <x-form.select
                    name="status"
                    label="Статус"
                    :options="[
                        '' => 'Все',
                        ...\App\Models\Order::STATUS_LABELS
                    ]"
                    :selected="request('status')"
                />

                <div class="flex grow items-end gap-2">
                <x-button class="!rounded-lg" variant="primary-outline" type="submit">Применить</x-button>
                <x-button href="{{ route('orders.index') }}" variant="ghost">Сброс</x-button>
                    <x-button class="ml-auto" type="button" variant="primary-outline" onclick="openStats()">
                        Статистика
                    </x-button>
                </div>
            </form>
        </div>

        <div class="flex-1 p-4 min-h-0">
            <div class="h-full overflow-auto">
                <x-table :headers="['Дата', 'ФИО', 'Телефон', 'ИНН', 'Компания', 'Адрес', 'Товары', 'Статус']">
                    @forelse($orders as $order)
                        <x-table.row>
                            <x-table.cell>{{ $order->created_at?->format('Y-m-d') }}</x-table.cell>
                            <x-table.cell>{{ $order->fio }}</x-table.cell>
                            <x-table.cell>{{ $order->phone }}</x-table.cell>
                            <x-table.cell>{{ $order->inn }}</x-table.cell>
                            <x-table.cell>{{ $order->company }}</x-table.cell>
                            <x-table.cell>{{ $order->address }}</x-table.cell>
                            <x-table.cell>
                                <ul class="space-y-1">
                                    @foreach($order->products as $product)
                                        <li>{{ $product->name }} ({{ $product->quantity }}{{ \App\Models\OrderProduct::SHORT_UNIT_LABELS[$product->unit] }})</li>
                                    @endforeach
                                </ul>
                            </x-table.cell>
                            <x-table.cell>{{ \App\Models\Order::STATUS_LABELS[$order->status] }}</x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="8" class="text-center text-gray-500">
                                Заказы не найдены
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-table>

                <div class="mt-4">
                    <x-pagination :paginator="$orders" />
                </div>
            </div>
        </div>
    </div>

    @include('orders._stats')
</div>
@endsection

