<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест для отображения задач на главной странице.
     *
     * @return void
     */
    public function testIndexDisplaysTasks(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id,
        ]);

        /** @var Label $label */
        $label = Label::factory()->create();

        $task->labels()->attach($label);

        $this->actingAs($user);

        /** @var TestResponse $response */
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
    }

    /**
     * Тест для создания новой задачи.
     *
     * @return void
     */
    public function testStoreCreatesNewTask(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Label $label */
        $label = Label::factory()->create();

        $this->actingAs($user);

        /** @var TestResponse $response */
        $response = $this->post(route('tasks.store'), [
            'name' => 'Test Task',
            'description' => 'This is a test task',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $user->id,
            'labels' => [$label->id],
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    /**
     * Тест для обновления существующей задачи.
     *
     * @return void
     */
    public function testUpdateModifiesExistingTask(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id
        ]);

        $this->actingAs($user);

        /** @var TestResponse $response */
        $response = $this->put(route('tasks.update', $task->id), [
            'name' => 'Updated Task',
            'status_id' => $taskStatus->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => 'Updated Task']);
    }

    /**
     * Тест для удаления задачи.
     *
     * @return void
     */
    public function testDestroyDeletesTask(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id
        ]);

        $this->actingAs($user);

        /** @var TestResponse $response */
        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * Тест для отображения деталей задачи.
     *
     * @return void
     */
    public function testShowDisplaysTaskDetails(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id
        ]);

        $this->actingAs($user);

        /** @var TestResponse $response */
        $response = $this->get(route('tasks.show', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
        $response->assertViewHas('task', $task);
    }
}