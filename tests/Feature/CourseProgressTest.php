<?php

declare(strict_types=1);

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Level;
use App\Models\User;

it('course progress percentage is calculated correctly', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lessons = Lesson::factory()->count(4)->create(['course_id' => $course->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    // Complete 2 out of 4 lessons
    LessonProgress::create([
        'user_id' => $user->id,
        'lesson_id' => $lessons[0]->id,
        'completed_at' => now(),
    ]);
    LessonProgress::create([
        'user_id' => $user->id,
        'lesson_id' => $lessons[1]->id,
        'completed_at' => now(),
    ]);

    expect($course->getUserProgress($user))->toBe(50.0);
});

it('user data is isolated per account', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    // User 1 completes lesson
    LessonProgress::create([
        'user_id' => $user1->id,
        'lesson_id' => $lesson->id,
        'completed_at' => now(),
    ]);

    // User 2's progress should be 0
    Enrollment::create(['user_id' => $user2->id, 'course_id' => $course->id]);

    expect($course->getUserProgress($user2))->toBe(0.0);
    expect($course->getUserProgress($user1))->toBe(100.0);
});
