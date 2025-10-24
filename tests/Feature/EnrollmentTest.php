<?php

declare(strict_types=1);

use App\Enums\StatusCoursesEnum;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Level;
use App\Models\User;

test('authenticated user can enroll in published course', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create([
        'level_id' => $level->id,
        'status' => 'published',
    ]);

    $response = $this->actingAs($user)->post(route('enroll', $course->slug));

    $response->assertRedirect(route('courses.show', $course->slug));

    expect(Enrollment::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->exists()
    )->toBeTrue();
});

test('guest cannot enroll in course', function () {
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);

    $response = $this->post(route('enroll', $course->slug));

    $response->assertRedirect(route('login'));
});

test('cannot enroll in draft course', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create([
        'level_id' => $level->id,
        'status' => StatusCoursesEnum::DRAFT->value,
    ]);

    $response = $this->actingAs($user)
        ->from(route('courses.show', $course->slug))
        ->post(route('enroll', $course->slug));

    // Should redirect back
    $response->assertRedirect();

    // Verify enrollment did not happen
    expect(Enrollment::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->exists()
    )->toBeFalse();
});

test('enrollment is idempotent', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);

    // Enroll twice
    $this->actingAs($user)->post(route('enroll', $course->slug));
    $this->actingAs($user)->post(route('enroll', $course->slug));

    expect(Enrollment::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->count()
    )->toBe(1);
});
