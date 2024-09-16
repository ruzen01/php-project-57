@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">{{ $task->name }}</h1>

    <div class="bg-white shadow-md rounded-md mb-4">
        <div class="p-4">
            <ul class="list-none mt-4">
                <!-- Имя -->
                <li class="py-2"><strong>Имя:</strong> {{ $task->name }}</li>

                <!-- Статус -->
                <li class="border-t border-gray-200 py-2"><strong>Статус:</strong> {{ $task->status->name }}</li>

                <!-- Описание -->
                <li class="border-t border-gray-200 py-2"><strong>Описание:</strong> {{ $task->description ?? 'Описание отсутствует' }}</li>

                <!-- Метки -->
                <li class="border-t border-gray-200 py-2">
                    <strong>Метки:</strong>
                    @if($task->labels->isEmpty())
                        <span>Метки отсутствуют</span>
                    @else
                        @foreach ($task->labels as $label)
                            <span class="inline-block px-2 py-1 rounded-full text-white bg-blue-500 mr-2">{{ $label->name }}</span>
                        @endforeach
                    @endif
                </li>
            </ul>
        </div>
    </div>

    @auth
    <div class="mb-4 flex gap-2">
        <a href="{{ route('tasks.edit', $task->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">Редактировать</a>
        @if($task->created_by_id == auth()->id())
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить задачу?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md">Удалить</button>
            </form>
        @endif
    </div>
    @endauth
</div>
@endsection