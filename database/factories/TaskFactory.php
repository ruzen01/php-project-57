<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
           'name' => $this->faker->sentence,
           'status_id' => TaskStatus::factory(), // Используем фабрику TaskStatus
        ];
    }
}
