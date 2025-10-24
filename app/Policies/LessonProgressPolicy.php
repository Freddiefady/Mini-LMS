<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LessonProgress;
use App\Models\User;

final class LessonProgressPolicy
{
    public function view(User $user, LessonProgress $progress): bool
    {
        return $user->id === $progress->user_id;
    }

    public function update(User $user, LessonProgress $progress): bool
    {
        return $user->id === $progress->user_id;
    }

    public function create(): true
    {
        return true;
        // Any authenticated user can track progress
    }
}
