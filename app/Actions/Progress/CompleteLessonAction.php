<?php

declare(strict_types=1);

namespace App\Actions\Progress;

use App\Mail\CourseCompletionEmail;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class CompleteLessonAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, Lesson $lesson, int $watchSeconds = 0): LessonProgress
    {
        return DB::transaction(function () use ($user, $lesson, $watchSeconds) {
            $progress = LessonProgress::query()->updateOrCreate([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ], [
                'completed_at' => now(),
                'watch_seconds' => max($progress->watch_seconds ?? 0, $watchSeconds),
                'started_at' => $progress->started_at ?? now(),
            ]);

            // Check if course is now complete
            $this->checkCourseCompletion($user, $lesson->course);

            return $progress;
        });
    }

    private function checkCourseCompletion(User $user, $course): void
    {
        $totalLessons = $course->getTotalLessons();
        $completedLessons = $course->lessons()
            ->whereHas('progress', function ($q) use ($user): void {
                $q->where('user_id', $user->id)
                    ->whereNotNull('completed_at');
            })
            ->count();

        if ($totalLessons > 0 && $completedLessons === $totalLessons) {
            // Avoid duplicate completions
            $existing = $user->courseCompletions()
                ->where('course_id', $course->id)
                ->exists();

            if (! $existing) {
                $course->completions()->create(['user_id' => $user->id]);
                Mail::to($user)->queue(new CourseCompletionEmail($user, $course));
            }
        }
    }
}
