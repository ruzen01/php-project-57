<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexDisplaysTasks(): void
    {
        $this->withoutExceptionHandling();

        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        /** @var \App\Models\TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var \App\Models\Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id,
        ]);

        /** @var \App\Models\Label $label */
        $label = Label::factory()->create();

        $task->labels()->attach($label);

        $this->actingAs($user);

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
    }

    public function testStoreCreatesNewTask(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $label = Label::factory()->create();

        $this->actingAs($user);

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

    public function testUpdateModifiesExistingTask(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        $this->actingAs($user);

        $response = $this->put(route('tasks.update', $task->id), [
            'name' => 'Updated Task',
            'status_id' => $taskStatus->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => 'Updated Task']);
    }

    public function testDestroyDeletesTask(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testShowDisplaysTaskDetails(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id, 'status_id' => $taskStatus->id]);

        $this->actingAs($user);

        $response = $this->get(route('tasks.show', $task->id));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
        $response->assertViewHas('task', $task);
    }
}
