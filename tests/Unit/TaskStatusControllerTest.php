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
    public function test_index_displays_all_task_statuses()
    {
        $this->authenticate(); // Аутентифицируем пользователя
        TaskStatus::factory()->count(3)->create();

        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
        $response->assertViewHas('taskStatuses');
    }

    // Тест для отображения формы создания нового статуса
    public function test_create_displays_create_form()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $response = $this->get(route('task_statuses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.create');
    }

    // Тест для создания нового статуса
    public function test_store_creates_new_task_status()
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
    public function test_store_validation_fails_if_name_is_empty()
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
    public function test_edit_displays_edit_form()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus->id));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.edit');
        $response->assertViewHas('task_status', $taskStatus);
    }

    // Тест для обновления статуса
    public function test_update_changes_existing_task_status()
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
    public function test_destroy_deletes_task_status()
    {
        $this->authenticate(); // Аутентифицируем пользователя

        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus->id));

        $response->assertRedirect(route('task_statuses.index'));

        // Вместо assertDeleted используйте assertDatabaseMissing
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->id]);
    }
}