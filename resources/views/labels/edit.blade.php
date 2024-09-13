@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать метку</h1>

    <form action="{{ route('labels.update', $label->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" name="name" class="form-control" value="{{ $label->name }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" class="form-control">{{ $label->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
</div>
@endsection