<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\CourseCompletion;

final readonly class FirstUserEnrollmentsQuery
{
    public function __construct(
        private string $record,
    ) {}

    public function builder()
    {
        return CourseCompletion::user()->enrollments()
            ->where('course_id', $this->record->course_id)
            ->first();
    }
}
