@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Статусы</h1>

    <!-- Кнопка "Создать новый статус" доступна только авторизованным пользователям -->
    @auth
    <a href="{{ route('task_statuses.create') }}" class="btn btn-primary me-2">Создать статус</a>
    @endauth

    <!-- Таблица статусов -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Дата создания</th>
                @auth <!-- Показывать столбец "Действия" только авторизованным пользователям -->
                <th>Действия</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($taskStatuses as $status)
            <tr>
                <td>{{ $status->id }}</td>
                <td>{{ $status->name }}</td>
                <td>{{ $status->created_at->format('d.m.Y') }}</td>
                @auth
                <td>
                    <!-- Ссылка для удаления (красный текст, без подчеркивания) -->
                    <a href="#"
                       class="text-danger"
                       style="text-decoration: none;"
                       onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить этот статус?')) { document.getElementById('delete-form-{{ $status->id }}').submit(); }">
                        Удалить
                    </a>

                    <!-- Скрытая форма для отправки DELETE-запроса -->
                    <form id="delete-form-{{ $status->id }}" action="{{ route('task_statuses.destroy', $status->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <!-- Ссылка для изменения (синий текст, без подчеркивания) -->
                    <a href="{{ route('task_statuses.edit', $status->id) }}" class="text-primary" style="text-decoration: none;">Изменить</a>
                </td>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection