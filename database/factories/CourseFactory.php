<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
final class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'level_id' => Level::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.now()->timestamp.random_int(1000, 9999),
            'description' => fake()->paragraph(3),
            'image_url' => 'https://picsum.photos/800/400?random='.random_int(1, 1000),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'published',
        ]);
    }
}
