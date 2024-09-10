@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Task</h1>

    <!-- Вывод флеш-сообщений -->
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

    <form method="POST" action="{{ route('tasks.update', $task->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="status_id">Status</label>
            <select name="status_id" id="status_id" class="form-control">
                @foreach ($task_statuses as $status)
                    <option value="{{ $status->id }}" @if($task->status_id == $status->id) selected @endif>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="assigned_to_id">Assign To</label>
            <select name="assigned_to_id" id="assigned_to_id" class="form-control">
                <option value="">None</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if($task->assigned_to_id == $user->id) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Блок для выбора меток -->
        <div class="form-group">
            <label for="labels">Labels</label>
            <select name="labels[]" id="labels" class="form-control" multiple>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}" 
                        @if($task->labels->contains($label->id)) selected @endif>
                        {{ $label->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Конец блока для выбора меток -->

        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
</div>
@endsection