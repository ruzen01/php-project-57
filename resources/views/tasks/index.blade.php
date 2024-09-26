@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Задачи</h1>

    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1 md:w-1/4">
                <label for="status_id" class="block text-gray-700 text-sm font-medium mb-1"></label>
                <select name="filter[status_id]" id="status_id" class="block w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Статус</option>
                    @foreach($taskStatuses as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 md:w-1/4">
                <label for="created_by_id" class="block text-gray-700 text-sm font-medium mb-1"></label>
                <select name="filter[created_by_id]" id="created_by_id" class="block w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Автор</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.created_by_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 md:w-1/4">
                <label for="assigned_to_id" class="block text-gray-700 text-sm font-medium mb-1"></label>
                <select name="filter[assigned_to_id]" id="assigned_to_id" class="block w-full h-10 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Исполнитель</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.assigned_to_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 h-10">Применить</button>

                <!-- Кнопка создания задачи -->
                @auth
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 h-10">Создать задачу</a>
                @endauth
            </div>
        </div>
    </form>

    <!-- Таблица задач с горизонтальной и вертикальной прокруткой -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white table-fixed">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Имя</th>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Автор</th>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Исполнитель</th>
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Дата создания</th>
                    @auth
                    <th class="px-2 py-2 text-left text-sm font-medium text-gray-500 uppercase">Действия</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-2 py-2 text-sm text-gray-900">{{ $task->id }}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 truncate max-w-xs">{{ $task->status->name }}</td>
                    <td class="px-2 py-2 text-sm text-blue-600 hover:text-blue-900 truncate max-w-xs">
                        <a href="{{ route('tasks.show', $task->id) }}" class="no-underline">{{ $task->name }}</a>
                    </td>
                    <td class="px-2 py-2 text-sm text-gray-900 truncate max-w-xs">{{ $task->creator ? $task->creator->name : '' }}</td>
                    <td class="px-2 py-2 text-sm text-gray-900 truncate max-w-xs">{{ $task->assignee ? $task->assignee->name : '' }}</td>
                    <td class="px-2 py-2 text-sm text-gray-900">{{ $task->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td class="px-2 py-2 text-sm text-gray-900">
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
            <!-- Пагинация -->
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
    </div>
</div>
@endsection