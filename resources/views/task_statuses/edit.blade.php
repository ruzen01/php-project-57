@extends('layouts.app')

@section('content')
    <h1>Редактировать статус</h1>

    <form action="{{ route('task_statuses.update', $task_status->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название статуса</label>
            <input type="text" name="name" id="name" value="{{ $task_status->name }}" required>
        </div>
        <button type="submit">Обновить</button>
    </form>
@endsection