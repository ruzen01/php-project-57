<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Менеджер задач') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .navbar {
            background-color: #3c3c3c;
        }

        .navbar-brand,
        .nav-link {
            color: #adadad !important;
        }

        .navbar-brand:hover,
        .nav-link:hover {
            color: white !important;
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

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Логотип -->
                <a class="navbar-brand" href="{{ url('/') }}">Менеджер задач</a>

                <!-- Кнопка для мобильных устройств -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Основное меню -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Навигационные ссылки -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tasks.index') }}">Задачи</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('task_statuses.index') }}">Статусы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('labels.index') }}">Метки</a>
                        </li>
                    </ul>

                    <!-- Блок с кнопками аутентификации -->
                    <div class="d-flex">
                        @guest
                        <!-- Кнопки для гостей -->
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">Вход</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Регистрация</a>
                        @else
                        <!-- Кнопка "Выход" для авторизованных пользователей -->
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary" dusk="logout-button">Выход</button>
                        </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>