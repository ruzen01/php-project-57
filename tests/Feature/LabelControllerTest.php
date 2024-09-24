<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    // Тест на получение списка меток
    public function testIndexDisplaysLabels(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('labels.index'));

        $response->assertStatus(200);
        $response->assertViewIs('labels.index');
        $response->assertViewHas('labels');
    }

    // Тест на создание метки
    public function testStoreCreatesNewLabel(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('labels.store'), [
            'name' => 'Test Label',
            'description' => 'This is a test label',
        ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'Test Label']);
    }

    // Тест на редактирование метки
    public function testEditDisplaysEditForm(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('labels.edit', $label->id));

        $response->assertStatus(200);
        $response->assertViewIs('labels.edit');
        $response->assertViewHas('label', $label);
    }

    // Тест на обновление метки
    public function testUpdateModifiesExistingLabel(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $this->actingAs($user);

        $response = $this->put(route('labels.update', $label->id), [
            'name' => 'Updated Label',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'Updated Label']);
    }

    // Тест на удаление метки
    public function testDestroyDeletesLabel(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('labels.destroy', $label->id));

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    // Тест на удаление метки, связанной с задачами
    public function testDestroyFailsIfLabelIsLinkedToTasks(): void
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();
        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        /** @var Task $task */
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
            'status_id' => $taskStatus->id,
        ]);

        $task->labels()->attach($label);

        $this->actingAs($user);

        $response = $this->delete(route('labels.destroy', $label->id));

        $response->assertRedirect(route('labels.index'));
        $response->assertSessionHas('error', 'Не удалось удалить метку');
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }
}
