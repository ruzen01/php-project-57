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
                    <a href="{{ route('register') }}" class="btn btn-primary me-2">Регистрация</a>
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