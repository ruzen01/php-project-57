<nav class="shadow-sm bg-white dark:bg-gray-800">
    <div class="container mx-auto flex justify-between items-center py-4">

        <!-- Логотип -->
        <a class="text-xl font-bold text-gray-800 dark:text-white" href="{{ url('/') }}">Менеджер задач</a>

        <!-- Кнопка для открытия меню на мобильных устройствах -->
        <div class="lg:hidden">
            <button id="mobile-menu-button" class="text-gray-800 dark:text-white focus:outline-none">
                <!-- Иконка меню -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

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

    <!-- Мобильное меню -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-gray-800">
        <ul class="flex flex-col space-y-4 py-4 text-gray-800 dark:text-white">
            <li>
                <a class="hover:text-blue-500" href="{{ route('tasks.index') }}">Задачи</a>
            </li>
            <li>
                <a class="hover:text-blue-500" href="{{ route('task_statuses.index') }}">Статусы</a>
            </li>
            <li>
                <a class="hover:text-blue-500" href="{{ route('labels.index') }}">Метки</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Скрипт для управления мобильным меню -->
<script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>