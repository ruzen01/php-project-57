<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_unauthenticated_users_from_profile_routes()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect('/login');

        $response = $this->patch(route('profile.update'));
        $response->assertRedirect('/login');

        $response = $this->delete(route('profile.destroy'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_profile_routes()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);

        $response = $this->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
        $response->assertStatus(302);

        $response = $this->delete(route('profile.destroy'));
        $response->assertStatus(302);
    }

    /** @test */
    public function it_redirects_unauthenticated_users_from_task_routes()
    {
        $response = $this->get(route('tasks.create'));
        $response->assertRedirect('/login');

        $response = $this->post(route('tasks.store'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_create_update_and_delete_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a task
        $response = $this->post(route('tasks.store'), [
            'name' => 'New Task',
            'description' => 'Task description',
            'status_id' => 1,
            'created_by_id' => $user->id
        ]);
        $response->assertStatus(302);

        // Update a task
        $task = \App\Models\Task::factory()->create(['created_by_id' => $user->id]);
        $response = $this->patch(route('tasks.update', $task->id), [
            'name' => 'Updated Task',
        ]);
        $response->assertStatus(302);

        // Delete a task
        $response = $this->delete(route('tasks.destroy', $task->id));
        $response->assertStatus(302);
    }

    /** @test */
    public function it_redirects_unauthenticated_users_from_label_routes()
    {
        $response = $this->get(route('labels.create'));
        $response->assertRedirect('/login');

        $response = $this->post(route('labels.store'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_create_update_and_delete_labels()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a label
        $response = $this->post(route('labels.store'), [
            'name' => 'New Label',
            'description' => 'Label description',
        ]);
        $response->assertStatus(302);

        // Update a label
        $label = \App\Models\Label::factory()->create();
        $response = $this->patch(route('labels.update', $label->id), [
            'name' => 'Updated Label',
        ]);
        $response->assertStatus(302);

        // Delete a label
        $response = $this->delete(route('labels.destroy', $label->id));
        $response->assertStatus(302);
    }
}