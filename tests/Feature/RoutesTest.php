<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    // Тест на публичный маршрут главной страницы
    public function testHomepageIsAccessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200); // Проверяем, что главная страница доступна
    }

    // Тест на маршрут dashboard с аутентификацией
    public function testDashboardRedirectsToHomeIfAuthenticated(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertRedirect('/'); // Проверяем, что авторизованного пользователя редиректит на главную
    }

    // Тест на маршрут профиля (требуется авторизация)
    public function testProfileRoutesRequireAuthentication(): void
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем, что авторизованный пользователь может получить доступ
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
    }

    // Тест на публичные маршруты задач, статусов и меток
    public function testPublicTaskStatusesRouteIsAccessible(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут статусов задач доступен
    }

    public function testPublicTasksRouteIsAccessible(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут задач доступен
    }

    public function testPublicLabelsRouteIsAccessible(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200); // Проверяем, что публичный маршрут меток доступен
    }

    // Тесты на защищенные маршруты задач (создание, редактирование, удаление)
    public function testTaskRoutesRequireAuthentication(): void
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('tasks.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);
    }

    public function testLabelRoutesRequireAuthentication(): void
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('labels.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);
    }

    public function testTaskStatusRoutesRequireAuthentication(): void
    {
        // Проверяем, что без авторизации доступ запрещен
        $response = $this->get(route('task_statuses.create'));
        $response->assertRedirect('/login');

        // Авторизуем пользователя
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Проверяем доступ для авторизованного пользователя
        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(200);
    }

    // Тест на публичный просмотр конкретной задачи
    public function testPublicTaskShowRouteIsAccessible(): void
    {
        /** @var User $user */
        $user = User::factory()->create(); // Создаем пользователя
        /** @var TaskStatus $status */
        $status = TaskStatus::factory()->create(); // Создаем статус задачи

        // Создаем задачу, указывая пользователя и статус
        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id, // Привязываем пользователя
            'status_id' => $status->id,   // Привязываем статус задачи
        ]);

        // Выполняем запрос на просмотр задачи
        $response = $this->get(route('tasks.show', $task->id));

        // Проверяем статус ответа
        $response->assertStatus(200);
    }
}