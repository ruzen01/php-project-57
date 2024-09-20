@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Метки</h1>

    <!-- Кнопка создания метки -->
    @auth
    <div class="mb-4 text-right">
        <a href="{{ route('labels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Создать метку
        </a>
    </div>
    @endauth

    <!-- Таблица меток -->
    <div class="overflow-hidden rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 bg-white w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase truncate">Имя</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Описание</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Дата создания</th>
                    @auth
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Действия</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($labels as $label)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $label->id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 truncate" title="{{ $label->name }}">{{ $label->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 truncate" title="{{ $label->description ?? 'Описание отсутствует' }}">{{ $label->description ?? 'Описание отсутствует' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $label->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td class="px-4 py-2 text-sm text-gray-900">
                        <a href="{{ route('labels.edit', $label->id) }}" class="text-blue-500 hover:text-blue-700">Изменить</a>
                        <a href="#" class="text-red-500 hover:text-red-700 ml-2" onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить эту метку?')) { document.getElementById('delete-form-{{ $label->id }}').submit(); }">Удалить</a>
                        <form id="delete-form-{{ $label->id }}" action="{{ route('labels.destroy', $label->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection