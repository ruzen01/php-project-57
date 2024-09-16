@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Создать задачу</h1>

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

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="form-group">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Имя</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание</label>
            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="status_id" class="block text-gray-700 text-sm font-bold mb-2">Статус</label>
            <select name="status_id" id="status_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="" {{ old('status_id') == '' ? 'selected' : '' }}> </option>
                @foreach ($task_statuses as $status)
                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="assigned_to_id" class="block text-gray-700 text-sm font-bold mb-2">Исполнитель</label>
            <select name="assigned_to_id" id="assigned_to_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="" {{ old('assigned_to_id') == '' ? 'selected' : '' }}> </option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('assigned_to_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Блок для выбора меток -->
        <div class="form-group">
            <label for="labels" class="block text-gray-700 text-sm font-bold mb-2">Метки</label>
            <select name="labels[]" id="labels" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" multiple>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}" {{ in_array($label->id, old('labels', [])) ? 'selected' : '' }}>
                        {{ $label->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Конец блока для выбора меток -->

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Создать</button>
    </form>
</div>
@endsection