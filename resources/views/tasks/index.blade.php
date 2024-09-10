@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks List</h1>

    <!-- Таблица с задачами -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Labels</th> <!-- Новая колонка для меток -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status->name }}</td>
                <td>{{ $task->assigned_to ? $task->assigned_to->name : 'Unassigned' }}</td>

                <!-- Вывод меток, привязанных к задаче -->
                <td>
                    @foreach($task->labels as $label)
                        <span class="badge badge-info">{{ $label->name }}</span>
                    @endforeach
                </td>

                <td>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection