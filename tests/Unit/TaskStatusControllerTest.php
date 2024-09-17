<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testIndexDisplaysAllTaskStatuses(): void
    {
        TaskStatus::factory()->create(['name' => 'Status 1']);
        TaskStatus::factory()->create(['name' => 'Status 2']);

        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
        $response->assertSee('Status 1');
        $response->assertSee('Status 2');
    }

    public function testCreateDisplaysCreateForm(): void
    {
        $this->authenticate();

        $response = $this->get(route('task_statuses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.create');
    }

    public function testStoreCreatesNewTaskStatus(): void
    {
        $this->authenticate();

        $data = [
            'name' => 'New Status',
        ];

        $response = $this->post(route('task_statuses.store'), $data);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'New Status']);
    }

    public function testStoreValidationFailsIfNameIsEmpty(): void
    {
        $this->authenticate();

        $data = [
            'name' => '',
        ];

        $response = $this->post(route('task_statuses.store'), $data);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('task_statuses', ['name' => '']);
    }

    public function testEditDisplaysEditForm(): void
    {
        $this->authenticate();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus->id));

        $response->assertStatus(200);
        $response->assertViewIs('task_statuses.edit');
        $response->assertViewHas('task_status', $taskStatus);
    }

    public function testUpdateChangesExistingTaskStatus(): void
    {
        $this->authenticate();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->put(route('task_statuses.update', $taskStatus->id), [
            'name' => 'Updated Status',
        ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'Updated Status']);
    }

    public function testDestroyDeletesTaskStatus(): void
    {
        $this->authenticate();

        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus->id));

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus->id]);
    }
}
