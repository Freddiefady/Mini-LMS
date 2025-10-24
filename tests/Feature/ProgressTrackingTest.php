<?php

declare(strict_types=1);

use App\Actions\Progress\CompleteLessonAction;
use App\Mail\CourseCompletionEmail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\User;

it('lesson completion is tracked', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    $action = new CompleteLessonAction();
    $progress = $action->handle($user, $lesson, 120);

    expect($progress->completed_at)->not->toBeNull()
        ->and($progress->watch_seconds)->toBe(120);
});

it('course completion creates completion record and sends email', function () {
    Mail::fake();

    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lessons = Lesson::factory()->count(3)->create(['course_id' => $course->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    $action = new CompleteLessonAction();

    // Complete all lessons
    foreach ($lessons as $lesson) {
        $action->handle($user, $lesson);
    }

    expect($user->courseCompletions()->where('course_id', $course->id)->exists())->toBeTrue();

    Mail::assertQueued(CourseCompletionEmail::class);
});

it('completion email sent only once', function () {
    Mail::fake();

    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    $action = new CompleteLessonAction();

    // Complete lesson twice
    $action->handle($user, $lesson);
    $action->handle($user, $lesson);

    Mail::assertQueued(CourseCompletionEmail::class, 1);
});
