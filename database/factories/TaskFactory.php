<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status_id' => Status::factory(),
        ];
    }
}
