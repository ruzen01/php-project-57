@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Задачи</h1>


    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="row mb-3">
            <div class="col">
                <label for="status_id"></label>
                <select name="filter[status_id]" id="status_id" class="form-control">
                    <option value="">Статус</option>
                    @foreach($task_statuses as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="created_by_id"></label>
                <select name="filter[created_by_id]" id="created_by_id" class="form-control">
                    <option value="">Автор</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.created_by_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="assigned_to_id"></label>
                <select name="filter[assigned_to_id]" id="assigned_to_id" class="form-control">
                    <option value="">Исполнитель</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('filter.assigned_to_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col d-flex align-items-end"> <!-- Добавляем класс для вертикального выравнивания кнопки -->
                <button type="submit" class="btn btn-primary me-2">Применить</button>
                <!-- Кнопка для создания задачи, видна только авторизованным пользователям -->
                @auth
                <a href="{{ route('tasks.create') }}" class="btn btn-primary me-1">Создать задачу</a>
                @endauth
            </div>
        </div>
    </form>

    <!-- Таблица с задачами -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Статус</th>
                <th>Имя</th>
                <th>Автор</th>
                <th>Исполнитель</th>
                <th>Дата создания</th>
                @auth <!-- Проверка на авторизацию -->
                <th>Действия</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->status->name }}</td>
                <td><a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', $task->id) }}" style="text-decoration: none;">{{ $task->name }}</a></td>
                <td>{{ $task->creator ? $task->creator->name : 'Unknown' }}</td>
                <td>{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                <td>{{ $task->created_at->format('d.m.Y') }}</td>
                @auth
                <td>
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