<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_welcome_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_redirects_dashboard_to_home()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/');
    }

    /** @test */
    public function it_shows_task_statuses_index()
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_tasks_index()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_labels_index()
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_individual_task_page()
    {
        // Создаем тестовую задачу
        $task = \App\Models\Task::factory()->create();

        $response = $this->get(route('tasks.show', $task->id));
        $response->assertStatus(200);
    }
}