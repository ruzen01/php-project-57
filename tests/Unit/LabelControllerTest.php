<?php

namespace Tests\Unit;

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
    public function testIndexDisplaysLabels()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя и метку
        $user = User::factory()->create();
        $label = Label::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Запрашиваем страницу с метками
        $response = $this->get(route('labels.index'));

        // Проверяем, что запрос успешен и данные меток отображаются
        $response->assertStatus(200);
        $response->assertViewIs('labels.index');
        $response->assertViewHas('labels');
    }

    // Тест на создание метки
    public function testStoreCreatesNewLabel()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Создаем метку через POST запрос
        $response = $this->post(route('labels.store'), [
            'name' => 'Test Label',
            'description' => 'This is a test label'
        ]);

        // Проверяем, что метка создана и произошел редирект
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'Test Label']);
    }

    // Тест на редактирование метки
    public function testEditDisplaysEditForm()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя и метку
        $user = User::factory()->create();
        $label = Label::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Запрашиваем форму редактирования метки
        $response = $this->get(route('labels.edit', $label->id));

        // Проверяем, что запрос успешен и форма редактирования отображается
        $response->assertStatus(200);
        $response->assertViewIs('labels.edit');
        $response->assertViewHas('label', $label);
    }

    // Тест на обновление метки
    public function testUpdateModifiesExistingLabel()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя и метку
        $user = User::factory()->create();
        $label = Label::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Обновляем метку через PUT запрос
        $response = $this->put(route('labels.update', $label->id), [
            'name' => 'Updated Label',
            'description' => 'Updated description'
        ]);

        // Проверяем, что метка обновлена и произошел редирект
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'Updated Label']);
    }

    // Тест на удаление метки
    public function testDestroyDeletesLabel()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя и метку
        $user = User::factory()->create();
        $label = Label::factory()->create();

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Удаляем метку через DELETE запрос
        $response = $this->delete(route('labels.destroy', $label->id));

        // Проверяем, что метка удалена и произошел редирект
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    // Тест на удаление метки, связанной с задачами
    public function testDestroyFailsIfLabelIsLinkedToTasks()
    {
        $this->withoutExceptionHandling();

        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Создаем метку
        $label = Label::factory()->create();

        // Создаем статус задачи
        $taskStatus = TaskStatus::factory()->create();

        // Создаем задачу с привязкой к созданному пользователю и статусу
        $task = Task::factory()->create([
            'created_by_id' => $user->id, // Указываем пользователя, который создал задачу
            'status_id' => $taskStatus->id // Указываем статус задачи
        ]);

        // Связываем задачу с меткой
        $task->labels()->attach($label);

        // Аутентифицируем пользователя
        $this->actingAs($user);

        // Пытаемся удалить метку через DELETE запрос
        $response = $this->delete(route('labels.destroy', $label->id));

        // Проверяем, что метка не была удалена и произошел редирект с ошибкой
        $response->assertRedirect(route('labels.index'));
        $response->assertSessionHas('error', 'Не удалось удалить метку');
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }
}
