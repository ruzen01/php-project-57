@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $task->name }}</h1>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Описание</h5>
            <p class="card-text">{{ $task->description ?? 'Описание отсутствует' }}</p>

            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Статус:</strong> {{ $task->status->name }}</li>
                <li class="list-group-item"><strong>Автор:</strong> {{ $task->creator ? $task->creator->name : 'Неизвестно' }}</li>
                <li class="list-group-item"><strong>Исполнитель:</strong> {{ $task->assignee ? $task->assignee->name : 'Не назначен' }}</li>
                <li class="list-group-item"><strong>Дата создания:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</li>
                <li class="list-group-item"><strong>Дата обновления:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}</li>
                <li class="list-group-item">
                    <strong>Метки:</strong>
                    @if($task->labels->isEmpty())
                        <span>Метки отсутствуют</span>
                    @else
                        @foreach ($task->labels as $label)
                            <span class="badge bg-primary">{{ $label->name }}</span>
                        @endforeach
                    @endif
                </li>
            </ul>
        </div>
    </div>

    @auth
    <div class="mb-3">
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Редактировать</a>
        @if($task->created_by_id == auth()->id())
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        @endif
    </div>
    @endauth
</div>
@endsection