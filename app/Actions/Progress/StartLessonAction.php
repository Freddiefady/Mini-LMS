<?php

declare(strict_types=1);

namespace App\Actions\Progress;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class StartLessonAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, Lesson $lesson): LessonProgress
    {
        return DB::transaction(function () use ($user, $lesson) {
            $progress = LessonProgress::query()->firstOrCreate([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ], [
                'started_at' => now(),
            ]);

            if ($progress->wasRecentlyCreated === false && $progress->started_at === null) {
                $progress->update(['started_at' => now()]);
            }

            return $progress;
        });
    }
}
