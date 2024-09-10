<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Менеджер задач</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #3c3c3c;
        }
        .navbar-brand {
            color: white !important;
        }
        .nav-link {
            color: #adadad !important;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Менеджер задач</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="{{ url('/tasks') }}">Задачи</a>
                <a class="nav-link" href="{{ url('/task_statuses') }}">Статусы</a>
                <a class="nav-link" href="{{ url('/labels') }}">Метки</a>
            </div>
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary me-2">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Регистрация</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="display-4">Привет от Хекслета!</h1>
        <p class="lead">Это простой менеджер задач на Laravel</p>
        <a href="https://ru.hexlet.io/" class="btn btn-primary mt-3">Нажми меня</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>