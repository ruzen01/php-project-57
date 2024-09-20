@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Задачи</h1>

    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-end gap-4">
            <!-- Ваши фильтры (без изменений) -->
        </div>
    </form>

    <!-- Таблица задач -->
    <div class="overflow-hidden rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 bg-white w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase truncate">Имя</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Автор</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Исполнитель</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Дата создания</th>
                    @auth
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Действия</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $task->id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $task->status->name }}</td>
                    <td class="px-4 py-2 text-sm text-blue-600 hover:text-blue-900 truncate" title="{{ $task->name }}">
                        <a href="{{ route('tasks.show', $task->id) }}" class="no-underline">{{ $task->name }}</a>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $task->creator ? $task->creator->name : 'Unknown' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $task->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td class="px-4 py-2 text-sm text-gray-900">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500 hover:text-blue-700">Изменить</a>
                        @if($task->created_by_id == auth()->id())
                        <a href="#" class="text-red-500 hover:text-red-700 ml-2" onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить эту задачу?')) { document.getElementById('delete-task-form-{{ $task->id }}').submit(); }">Удалить</a>
                        <form id="delete-task-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endif
                    </td>
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection