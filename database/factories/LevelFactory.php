<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Level>
 */
final class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Beginner', 'Intermediate', 'Advanced', 'Expert']),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
