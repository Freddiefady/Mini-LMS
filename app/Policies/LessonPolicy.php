<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

final class LessonPolicy
{
    public function view(User $user, Lesson $lesson): bool
    {
        // Free preview or admin
        if ($lesson->is_free_preview || $user->is_admin) {
            return true;
        }

        // Must be enrolled
        return $lesson->course->enrollments()
            ->where('user_id', $user->id)
            ->exists();
    }
}
