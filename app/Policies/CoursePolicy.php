<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

final class CoursePolicy
{
    public function view(?User $user, Course $course): true
    {
        // Can view if published or if admin
        if ($course->published()) {
            return true;
        }

        return $user->is_admin;
    }

    public function viewAny(): true
    {
        return true;
    }
}
