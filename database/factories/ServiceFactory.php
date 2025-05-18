<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'duration' => $this->faker->numberBetween(30, 180),
            'category_id' => Category::factory(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
} 