@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Статусы</h1>

    <!-- Кнопка создания статуса -->
    @auth
    <div class="mb-4 text-right">
        <a href="{{ route('task_statuses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Создать статус
        </a>
    </div>
    @endauth

    <!-- Таблица статусов -->
    <div class="overflow-x-auto ">
        <table class="min-w-full divide-y divide-gray-200 bg-white w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Имя</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Дата создания</th>
                    @auth
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Действия</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($taskStatuses as $status)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $status->id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 truncate max-w-xs">{{ $status->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $status->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td class="px-4 py-2 text-sm text-gray-900">
                        <a href="{{ route('task_statuses.edit', $status->id) }}" class="text-blue-500 hover:text-blue-700">Изменить</a>
                        <a href="#" class="text-red-500 hover:text-red-700 ml-2" onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить этот статус?')) { document.getElementById('delete-form-{{ $status->id }}').submit(); }">Удалить</a>
                        <form id="delete-form-{{ $status->id }}" action="{{ route('task_statuses.destroy', $status->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Пагинация -->
        <div class="mt-4">
            {{ $taskStatuses->links() }}
        </div>
    </div>
</div>
@endsection