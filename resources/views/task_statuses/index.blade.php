@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Статусы</h1>

    <!-- Кнопка "Создать новый статус" доступна только авторизованным пользователям -->
    @auth
    <a href="{{ route('task_statuses.create') }}" class="btn btn-success mb-3">Создать статус</a>
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
                    <!-- Кнопка "Удалить" -->
                    <form action="{{ route('task_statuses.destroy', $status->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>

                        <!-- Кнопка "Редактировать" -->
                        <a href="{{ route('task_statuses.edit', $status->id) }}" class="btn btn-primary">Изменить</a>
                        
                    </form>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection