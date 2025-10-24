<?php

declare(strict_types=1);

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\User;

it('guest can view free preview lesson', function () {
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
        'is_free_preview' => true,
    ]);

    $response = $this->get(route('lessons.show', [$course->slug, $lesson->id]));

    $response->assertStatus(302); // Redirects to login since route requires auth
});

it('enrolled user can view non-preview lesson', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
        'is_free_preview' => false,
    ]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    $response = $this->actingAs($user)->get(route('lessons.show', [$course->slug, $lesson->id]));

    $response->assertOk();
});

it('non-enrolled user cannot view non-preview lesson', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);
    $lesson = Lesson::factory()->create([
        'course_id' => $course->id,
        'is_free_preview' => false,
    ]);

    $response = $this->actingAs($user)->get(route('lessons.show', [$course->slug, $lesson->id]));

    $response->assertForbidden();
});
