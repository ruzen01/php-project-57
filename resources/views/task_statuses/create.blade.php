@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создать статус</h1>

        <form action="{{ route('task_statuses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название статуса</label>
                <input type="text" name="name" id="name" class="form-control">

            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection