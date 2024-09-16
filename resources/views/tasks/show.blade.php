@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold">{{ $task->name }}</h1>

    <div class="card mb-3 shadow-md rounded-md">
        <div class="card-body p-4">
            <h5 class="card-title text-lg font-medium mb-2">Описание</h5>
            <p class="card-text text-gray-700">{{ $task->description ?? 'Описание отсутствует' }}</p>

            <ul class="list-group list-group-flush">
                <li class="list-group-item py-2"><strong>Статус:</strong> {{ $task->status->name }}</li>
                <li class="list-group-item py-2"><strong>Автор:</strong> {{ $task->creator ? $task->creator->name : 'Неизвестно' }}</li>
                <li class="list-group-item py-2"><strong>Исполнитель:</strong> {{ $task->assignee ? $task->assignee->name : 'Не назначен' }}</li>
                <li class="list-group-item py-2"><strong>Дата создания:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</li>
                <li class="list-group-item py-2"><strong>Дата обновления:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}</li>
                <li class="list-group-item py-2">
                    <strong>Метки:</strong>
                    @if($task->labels->isEmpty())
                        <span>Метки отсутствуют</span>
                    @else
                        @foreach ($task->labels as $label)
                            <span class="inline-block px-2 py-1 rounded-full text-white bg-blue-500">{{ $label->name }}</span>
                        @endforeach
                    @endif
                </li>
            </ul>
        </div>
    </div>

    @auth
    <div class="mb-3 flex gap-2">
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-700 text-white">Редактировать</a>
        @if($task->created_by_id == auth()->id())
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-md bg-red-500 hover:bg-red-700 text-white">Удалить</button>
            </form>
        @endif
    </div>
    @endauth
</div>
@endsection