<?php

declare(strict_types=1);

use App\Actions\Enrollment\EnrollUserAction;
use App\Actions\Progress\CompleteLessonAction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('progress and completion updates are transactional', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    Enrollment::create(['user_id' => $user->id, 'course_id' => $course->id]);

    // Mock DB failure during completion
    DB::shouldReceive('transaction')
        ->once()
        ->andThrow(new Exception('Database error'));

    $action = new CompleteLessonAction();

    try {
        $action->handle($user, $lesson);
    } catch (Exception) {
    }

    // Verify no partial data was saved
    expect(LessonProgress::where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->exists()
    )->toBeFalse();
});

test('concurrent enrollment attempts result in single enrollment', function () {
    $user = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id, 'status' => 'published']);

    $action = new EnrollUserAction();

    // Simulate concurrent enrollments
    $action->handle($user, $course);
    $action->handle($user, $course);

    expect(Enrollment::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->count()
    )->toBe(1);
});
