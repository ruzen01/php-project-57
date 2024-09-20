@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 overflow-x-hidden"> <!-- Убедитесь, что горизонтальная прокрутка отключена -->
    <h1 class="text-2xl font-bold mb-4 break-all">{{ $task->name }}</h1>

    <div class="bg-white shadow-md rounded-md mb-4">
        <div class="p-4">
            <!-- Таблица для отображения данных -->
            <table class="table-auto w-full"> <!-- Убедитесь, что таблица не превышает ширину контейнера -->
                <tbody>
                    <!-- Имя -->
                    <tr class="border-t">
                        <th class="text-left py-2 pr-4 font-medium text-gray-700">Имя</th>
                        <td class="py-2 break-words whitespace-normal">{{ $task->name }}</td>
                    </tr>

                    <!-- Статус -->
                    <tr class="border-t">
                        <th class="text-left py-2 pr-4 font-medium text-gray-700">Статус</th>
                        <td class="py-2 break-words whitespace-normal">{{ $task->status->name }}</td>
                    </tr>

                    <!-- Описание -->
                    <tr class="border-t">
                        <th class="text-left py-2 pr-4 font-medium text-gray-700">Описание</th>
                        <td class="py-2 break-words whitespace-normal">{{ $task->description ?? 'Описание отсутствует' }}</td>
                    </tr>

                    <!-- Метки -->
                    <tr class="border-t">
                        <th class="text-left py-2 pr-4 font-medium text-gray-700">Метки</th>
                        <td class="py-2 break-words whitespace-normal">
                            @if($task->labels->isEmpty())
                                <span>Метки отсутствуют</span>
                            @else
                                @foreach ($task->labels as $label)
                                    <span class="inline-block px-2 py-1 rounded-full text-white bg-blue-500 mr-2 break-words whitespace-normal">{{ $label->name }}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
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