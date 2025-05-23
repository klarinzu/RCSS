<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
} 