@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Метки</h1>

    <!-- Флеш-сообщения -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Кнопка "Создать метку" доступна только для авторизованных пользователей -->
    @auth
        <a href="{{ route('labels.create') }}" class="btn btn-primary mb-3">Создать метку</a>
    @endauth

    <!-- Таблица меток -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Дата создания</th>
                @auth
                <th>Действия</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $label)
                <tr>
                    <td>{{ $label->id }}</td>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description ?? 'Описание отсутствует' }}</td>
                    <td>{{ $label->created_at->format('d.m.Y') }}</td>
                    @auth
                    <td>
                        <!-- Ссылка для удаления (красный текст, без подчеркивания) -->
                        <a href="#"
                           class="text-danger"
                           style="text-decoration: none;"
                           onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить эту метку?')) { document.getElementById('delete-form-{{ $label->id }}').submit(); }">
                            Удалить
                        </a>

                        <!-- Скрытая форма для отправки DELETE-запроса -->
                        <form id="delete-form-{{ $label->id }}" action="{{ route('labels.destroy', $label->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <!-- Ссылка для изменения (синий текст, без подчеркивания) -->
                        <a href="{{ route('labels.edit', $label->id) }}" class="text-primary" style="text-decoration: none;">Изменить</a>
                        
                    </td>
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection