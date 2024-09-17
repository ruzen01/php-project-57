<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    // Тест на публичный маршрут главной страницы
    public function test_homepage_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200); // Проверяем, что главная страница доступна
    }

    // Тест на маршрут dashboard с аутентификацией
    public function test_dashboard_redirects_to_home_if_authenticated()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertRedirect('/'); // Проверяем, что авторизованного пользователя редиректит на главную
    }

    // Тест на маршрут профиля (требуется авторизация)
    public function test_profile_routes_require_authentication()
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем, что авторизованный пользователь может получить доступ
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
    }

    // Тест на публичные маршруты задач, статусов и меток
    public function test_public_task_statuses_route_is_accessible()
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут статусов задач доступен
    }

    public function test_public_tasks_route_is_accessible()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут задач доступен
    }

    public function test_public_labels_route_is_accessible()
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут меток доступен
    }

    // Тесты на защищенные маршруты задач (создание, редактирование, удаление)
    public function test_task_routes_require_authentication()
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('tasks.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);
    }

    public function test_label_routes_require_authentication()
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('labels.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);
    }

    public function test_task_status_routes_require_authentication()
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('task_statuses.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(200);
    }

    // Тест на публичный просмотр конкретной задачи
    public function test_public_task_show_route_is_accessible()
    {
        $user = User::factory()->create(); // Создаем пользователя
        $status = \App\Models\TaskStatus::factory()->create(); // Создаем статус задачи

        // Создаем задачу, указывая пользователя и статус
        $task = \App\Models\Task::factory()->create([
            'created_by_id' => $user->id, // Привязываем пользователя
            'status_id' => $status->id,   // Привязываем статус задачи
        ]);

        // Выполняем запрос на просмотр задачи
        $response = $this->get(route('tasks.show', $task->id));

        // Проверяем статус ответа
        $response->assertStatus(200);
    }
}
