@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Задачи</h1>


    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="grid grid-cols-4 gap-4 mb-3">
            <div>
                <label for="status_id" class="block text-gray-700 text-sm font-bold mb-2">Статус</label>
                <select name="filter[status_id]" id="status_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Статус</option>
                    @foreach($task_statuses as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="created_by_id" class="block text-gray-700 text-sm font-bold mb-2">Автор</label>
                <select name="filter[created_by_id]" id="created_by_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Автор</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.created_by_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="assigned_to_id" class="block text-gray-700 text-sm font-bold mb-2">Исполнитель</label>
                <select name="filter[assigned_to_id]" id="assigned_to_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Исполнитель</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.assigned_to_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Применить</button>
                <!-- Кнопка для создания задачи, видна только авторизованным пользователям -->
                @auth
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">Создать задачу</a>
                @endauth
            </div>
        </div>
    </form>

    <!-- Таблица с задачами -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Статус</th>
                <th class="px-4 py-2">Имя</th>
                <th class="px-4 py-2">Автор</th>
                <th class="px-4 py-2">Исполнитель</th>
                <th class="px-4 py-2">Дата создания</th>
                @auth <!-- Проверка на авторизацию -->
                <th class="px-4 py-2">Действия</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td class="px-4 py-2">{{ $task->id }}</td>
                <td class="px-4 py-2">{{ $task->status->name }}</td>
                <td class="px-4 py-2"><a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', $task->id) }}" style="text-decoration: none;">{{ $task->name }}</a></td>
                <td class="px-4 py-2">{{ $task->creator ? $task->creator->name : 'Unknown' }}</td>
                <td class="px-4 py-2">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                <td class="px-4 py-2">{{ $task->created_at->format('d.m.Y') }}</td>
                @auth
                <td class="px-4 py-2">
                    <!-- Ссылка для редактирования (синий текст, без подчеркивания) -->
                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-primary" style="text-decoration: none;">
                        Изменить
                    </a>

                    <!-- Ссылка для удаления (красный текст, без подчеркивания), только для автора задачи -->
                    @if($task->created_by_id == auth()->id())
                    <a href="#"
                       class="text-danger"
                       style="text-decoration: none;"
                       onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить эту задачу?')) { document.getElementById('delete-task-form-{{ $task->id }}').submit(); }">
                        Удалить
                    </a>

                    <!-- Скрытая форма для отправки DELETE-запроса -->
                    <form id="delete-task-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </td>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

    <!-- Таблица с задачами -->
    <table class="table-auto w-full mt-3 border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Статус</th>
                <th class="px-4 py-2 text-left">Имя</th>
                <th class="px-4 py-2 text-left">Автор</th>
                <th class="px-4 py-2 text-left">Исполнитель</th>
                <th class="px-4 py-2 text-left">Дата создания</th>
                @auth <!-- Проверка на авторизацию -->
                <th class="px-4 py-2 text-left">Действия</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2">{{ $task->id }}</td>
                <td class="px-4 py-2">{{ $task->status->name }}</td>
                <td class="px-4 py-2"><a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', $task->id) }}" style="text-decoration: none;">{{ $task->name }}</a></td>
                <td class="px-4 py-2">{{ $task->creator ? $task->creator->name : 'Unknown' }}</td>
                <td class="px-4 py-2">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                <td class="px-4 py-2">{{ $task->created_at->format('d.m.Y') }}</td>
                @auth
                <td class="px-4 py-2">
                    <!-- Ссылка для редактирования (синий текст, без подчеркивания) -->
                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500 hover:text-blue-700" style="text-decoration: none;">
                        Изменить
                    </a>

                    <!-- Ссылка для удаления (красный текст, без подчеркивания), только для автора задачи -->
                    @if($task->created_by_id == auth()->id())
                    <a href="#"
                       class="text-red-500 hover:text-red-700"
                       style="text-decoration: none;"
                       onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить эту задачу?')) { document.getElementById('delete-task-form-{{ $task->id }}').submit(); }">
                        Удалить
                    </a>

                    <!-- Скрытая форма для отправки DELETE-запроса -->
                    <form id="delete-task-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: none;">
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
@endsection