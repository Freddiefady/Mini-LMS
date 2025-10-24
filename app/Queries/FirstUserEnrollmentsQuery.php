<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\CourseCompletion;
use App\Models\Enrollment;
use RuntimeException;
use Throwable;

final readonly class FirstUserEnrollmentsQuery
{
    /**
     * @throws Throwable
     */
    public function builder(?int $courseId): Enrollment
    {
        $completion = CourseCompletion::query()->find($courseId);

        throw_unless($completion, RuntimeException::class, 'Course completion not found');

        return $completion->user
            ->enrollments()
            ->where('course_id', $completion->course_id)
            ->first();
    }
}
