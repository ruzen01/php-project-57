@extends('layouts.app')

@section('content')
    <h1>Список статусов</h1>

    <a href="{{ route('task_statuses.create') }}">Создать новый статус</a>

    <ul>
        @foreach ($taskStatuses as $status)
            <li>{{ $status->name }}
                <a href="{{ route('task_statuses.edit', $status->id) }}">Редактировать</a>
                <form action="{{ route('task_statuses.destroy', $status->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection