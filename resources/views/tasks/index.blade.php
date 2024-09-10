@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks List</h1>

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
                <th>Task Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Labels</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status->name }}</td>
                <td>{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
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