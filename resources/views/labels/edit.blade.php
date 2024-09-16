@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Редактировать метку</h1>

    <form action="{{ route('labels.update', $label->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Поле для имени метки -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Название</label>
            <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $label->name }}" required>
            @error('name')
            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле для описания метки -->
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание</label>
            <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $label->description }}</textarea>
        </div>

        <!-- Кнопка для обновления -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Обновить
        </button>
    </form>
</div>
@endsection