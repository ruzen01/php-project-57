<nav class="shadow-sm bg-white dark:bg-gray-800">
    <div class="container mx-auto flex justify-between items-center py-4">

        <!-- Логотип -->
        <a class="text-xl font-bold text-gray-800 dark:text-white" href="{{ url('/') }}">Менеджер задач</a>

        <!-- Основное меню (центрирование кнопок) -->
        <div id="navbarNav" class="hidden lg:flex lg:items-center lg:space-x-4 mx-auto">
            <!-- Навигационные ссылки -->
            <ul class="flex space-x-6 text-gray-800 dark:text-white">
                <li class="nav-item">
                    <a class="hover:text-blue-500" href="{{ route('tasks.index') }}">Задачи</a>
                </li>
                <li class="nav-item">
                    <a class="hover:text-blue-500" href="{{ route('task_statuses.index') }}">Статусы</a>
                </li>
                <li class="nav-item">
                    <a class="hover:text-blue-500" href="{{ route('labels.index') }}">Метки</a>
                </li>
            </ul>
        </div>

        <!-- Блок с кнопками аутентификации (выравнивание по правому краю) -->
        <div class="flex items-center space-x-4 ml-auto">
            @guest
                <!-- Кнопки для гостей -->
                <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Вход</a>
                <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Регистрация</a>
            @else
                <!-- Ссылка "Выход" для авторизованных пользователей -->
                <a href="{{ route('logout') }}"
                   class="border border-blue-500 text-blue-500 px-4 py-2 rounded hover:bg-blue-500 hover:text-white"
                   dusk="logout-button"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Выход') }}
                </a>

                <!-- Скрытая форма для отправки POST-запроса -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
</nav>