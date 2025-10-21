<?php

declare(strict_types=1);

namespace App\Actions\Progress;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateWatchTimeAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, Lesson $lesson, int $seconds): LessonProgress
    {
        return DB::transaction(function () use ($user, $lesson, $seconds) {
            $progress = LessonProgress::query()->firstOrCreate([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ]);

            // Only increment, never decrement
            if ($seconds > $progress->watch_seconds) {
                $progress->update(['watch_seconds' => $seconds]);
            }

            return $progress;
        });
    }
}
