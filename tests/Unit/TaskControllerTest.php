<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_tasks()
    {
        $this->withoutExceptionHandling();

        // Создаем тестовые данные
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);
        $label = Label::factory()->create();
        $task->labels()->attach($label);

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Запрашиваем список задач
        $response = $this->get(route('tasks.index'));

        // Проверяем, что запрос успешен и данные задач отображаются
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
    }

    public function test_store_creates_new_task()
    {
        $this->withoutExceptionHandling();

        // Создаем тестовые данные
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $label = Label::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Создаем задачу через POST запрос
        $response = $this->post(route('tasks.store'), [
            'name' => 'Test Task',
            'description' => 'This is a test task',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $user->id,
            'labels' => [$label->id]
        ]);

        // Проверяем, что задача создана и произошел редирект
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    public function test_update_modifies_existing_task()
    {
        $this->withoutExceptionHandling();

        // Создаем тестовые данные
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Обновляем задачу через PUT запрос
        $response = $this->put(route('tasks.update', $task->id), [
            'name' => 'Updated Task',
            'status_id' => $taskStatus->id
        ]);

        // Проверяем, что задача обновлена и произошел редирект
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => 'Updated Task']);
    }

    public function test_destroy_deletes_task()
    {
        $this->withoutExceptionHandling();

        // Создаем тестовые данные
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Удаляем задачу через DELETE запрос
        $response = $this->delete(route('tasks.destroy', $task->id));

        // Проверяем, что задача удалена и произошел редирект
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_show_displays_task_details()
    {
        $this->withoutExceptionHandling();

        // Создаем тестовые данные
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Отображаем задачу через GET запрос
        $response = $this->get(route('tasks.show', $task->id));

        // Проверяем, что запрос успешен и задача отображается
        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
        $response->assertViewHas('task', $task);
    }
}
