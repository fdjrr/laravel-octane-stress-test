<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => random_int(1, 10),
            'slug'        => fake()->slug(),
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
            'category_id' => random_int(1, 10),
        ];
    }
}
