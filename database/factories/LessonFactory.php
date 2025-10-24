<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
final class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        static $order = 0;

        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'order' => ++$order,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => fake()->numberBetween(60, 3600),
            'is_free_preview' => fake()->boolean(20),
        ];
    }

    public function freePreview(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_free_preview' => true,
        ]);
    }
}
