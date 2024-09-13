@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Создать метку</h1>

    <form action="{{ route('labels.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" name="name" class="form-control">
            <!-- Сообщение об ошибке под полем -->
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
</div>
@endsection