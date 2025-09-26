@extends('layouts.app')

@section('content')
<x-layout.centered width="max-w-2xl">
    <x-card title="Создание заказа">
        <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <x-form.input name="fio" placeholder="ФИО" required class="md:col-span-2" />
                <x-form.input name="phone" placeholder="Телефон" required type="tel" />
                <x-form.input name="email" placeholder="Почта" type="email" />
                <x-form.input name="inn" placeholder="ИНН" />
                <x-form.input name="company" placeholder="Название компании" class="md:col-span-2" />
                <x-form.input name="address" placeholder="Адрес" class="md:col-span-2" />
            </div>

            <hr class="h-[2px] my-8 bg-gray-300 border-0 dark:bg-gray-300" />

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-3 gap-4 items-end" style="grid-template-columns: 1fr 10% 20%;">
                        <h4 class="font-medium text-gray-700">Товар</h4>
                        <h4 class="font-medium text-gray-700">Кол-во</h4>
                        <h4 class="font-medium text-gray-700">Ед. изм.</h4>
                    </div>
                    @foreach(range(0, 1) as $index)
                        <div class="grid grid-cols-3 gap-4 items-start" style="grid-template-columns: 1fr 10% 20%;">
                            <x-form.input
                                name="products[{{ $index }}][name]"
                                placeholder="Товары"
                                noAsterisk
                            />
                            <x-form.input
                                type="number"
                                name="products[{{ $index }}][quantity]"
                                placeholder="Количество"
                                value="0"
                                min="0"
                                class="w-16 text-center"
                            />
                            <x-form.select
                                name="products[{{ $index }}][unit]"
                                placeholder="Ед. изм."
                                :options="\App\Models\OrderProduct::UNIT_LABELS"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="h-[2px] mt-4 mb-7 bg-gray-300 border-0 dark:bg-gray-300" />

            <x-button type="submit" class="w-full justify-center">Создать заказ</x-button>
        </form>
    </x-card>
</x-layout.centered>
@endsection

