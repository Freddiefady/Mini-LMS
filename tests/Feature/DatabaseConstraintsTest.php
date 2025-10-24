<?php

declare(strict_types=1);

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Level;
use App\Models\User;

it('course slug must be unique', function () {
    $level = Level::factory()->create();
    $course1 = Course::factory()->create([
        'level_id' => $level->id,
        'slug' => 'test-course-123',
    ]);

    expect(fn () => Course::factory()->create([
        'level_id' => $level->id,
        'slug' => 'test-course-123',
    ]))->toThrow(Illuminate\Database\QueryException::class);
});

it('enrollment uniqueness constraint', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    expect(fn () => Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]))->toThrow(Illuminate\Database\QueryException::class);
});

it('lesson progress uniqueness constraint', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    LessonProgress::create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]);

    expect(fn () => LessonProgress::create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]))->toThrow(Illuminate\Database\QueryException::class);
});
