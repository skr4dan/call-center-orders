@extends('layouts.app')

@section('content')
<x-layout.centered>
    <x-card title="Авторизация">
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <x-form.input
                name="email"
                placeholder="Логин"
                required
                noAsterisk
            />

            <x-form.input
                type="password"
                name="password"
                placeholder="Пароль"
                required
                noAsterisk
            />

            <x-button type="submit" class="w-full justify-center">Войти</x-button>
        </form>
    </x-card>
</x-layout.centered>
@endsection

