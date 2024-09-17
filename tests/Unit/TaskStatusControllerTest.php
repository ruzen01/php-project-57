<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TaskStatus;
use App\Models\Task;
use App\Models\User; // Подключаем модель пользователя для аутентификации
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    // Метод для аутентификации пользователя
    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    // Тест для отображения списка всех статусов
    public function testIndexDisplaysAllTaskStatuses()
    {
        // Создаем несколько уникальных статусов задач
        TaskStatus::factory()->create(['name' => 'Status 1']);
        TaskStatus::factory()->create(['name' => 'Status 2']);

        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
        $response->assertSee('Status 1');
        $response->assertSee('Status 2');
    }

    // Тест для отображения формы создания нового статуса
    public function testCreateDisplaysCreateForm()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $response = $this->get(route('task_statuses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.create');
    }

    // Тест для создания нового статуса
    public function testStoreCreatesNewTaskStatus()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $data = [
            'name' => 'Новый статус',
        ];

        $response = $this->post(route('task_statuses.store'), $data);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'Новый статус']);
    }

    // Тест для валидации на создание статуса
    public function testStoreValidationFailsIfNameIsEmpty()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $data = [
            'name' => '',
        ];

        $response = $this->post(route('task_statuses.store'), $data);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('task_statuses', ['name' => '']);
    }

    // Тест для отображения формы редактирования
    public function testEditDisplaysEditForm()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus->id));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.edit');
        $response->assertViewHas('task_status', $taskStatus);
    }

    // Тест для обновления статуса
    public function testUpdateChangesExistingTaskStatus()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $taskStatus = TaskStatus::factory()->create();
        $data = [
            'name' => 'Обновленный статус',
        ];

        $response = $this->put(route('task_statuses.update', $taskStatus->id), $data);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'Обновленный статус']);
    }

    // Тест для удаления статуса
    public function testDestroyDeletesTaskStatus()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus->id));

        $response->assertRedirect(route('task_statuses.index'));

        // Вместо assertDeleted используйте assertDatabaseMissing
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->id]);
    }
}
