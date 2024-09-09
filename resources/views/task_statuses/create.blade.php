@extends('layouts.app')

@section('content')
    <h1>Создать новый статус</h1>

    <form action="{{ route('task_statuses.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Название статуса</label>
            <input type="text" name="name" id="name" required>
        </div>
        <button type="submit">Создать</button>
    </form>
@endsection