<?php

namespace Database\Factories;

use App\Models\Label;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabelFactory extends Factory
{
    protected $model = Label::class;

    public function definition()
    {
        return [
           'name' => $this->faker->word, // Генерация случайного имени метки
        ];
    }
}
