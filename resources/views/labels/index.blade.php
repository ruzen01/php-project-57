@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Labels</h1>

    <!-- Флеш-сообщения -->
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

    <a href="{{ route('labels.create') }}" class="btn btn-primary mb-3">Add New Label</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $label)
                <tr>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description ?? 'No description' }}</td>
                    <td>
                        <a href="{{ route('labels.edit', $label->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('labels.destroy', $label->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this label?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection