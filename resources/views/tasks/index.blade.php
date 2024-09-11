@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks List</h1>

    <!-- Кнопка для создания задачи, видна только авторизованным пользователям -->
    @auth
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Create Task</a>
    @endauth

    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="row mb-3">
            <div class="col">
                <label for="status_id">Status</label>
                <select name="filter[status_id]" id="status_id" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach($task_statuses as $id => $name)
                        <option value="{{ $id }}" {{ request('filter.status_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="created_by_id">Created By</label>
                <select name="filter[created_by_id]" id="created_by_id" class="form-control">
                    <option value="">All Creators</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ request('filter.created_by_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="assigned_to_id">Assigned To</label>
                <select name="filter[assigned_to_id]" id="assigned_to_id" class="form-control">
                    <option value="">All Assignees</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ request('filter.assigned_to_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="label_id">Labels</label>
                <select name="filter[label]" id="label_id" class="form-control">
                    <option value="">All Labels</option>
                    @foreach($labels as $id => $name)
                        <option value="{{ $id }}" {{ request('filter.label') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Таблица с задачами -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Name</th>
                <th>Author</th>
                <th>Assignee</th>
                <th>Created At</th>
                @auth <!-- Проверка на авторизацию -->
                <th>Actions</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->status->name }}</td>
                <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->name }}</a></td>
                <td>{{ $task->creator ? $task->creator->name : 'Unknown' }}</td>
                <td>{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                <td>{{ $task->created_at->format('d.m.Y') }}</td>
                @auth
                <td>
                    <!-- Кнопка редактирования доступна всем авторизованным -->
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a>

                    <!-- Кнопка удаления только для автора задачи -->
                    @if($task->created_by_id == auth()->id())
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection