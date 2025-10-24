<?php

declare(strict_types=1);

namespace App\Actions\Enrollment;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

final class EnrollUserAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, Course $course): Enrollment
    {
        throw_unless($course->isPublished(), Exception::class, 'Cannot enroll in draft courses.');

        return DB::transaction(fn () =>
            // Idempotent: create or return existing
            Enrollment::query()->firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]));
    }
}
