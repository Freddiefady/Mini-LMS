<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Enrollment\EnrollUserAction;
use App\Models\Course;
use Exception;

final class EnrollmentController extends Controller
{
    public function enroll(Course $course, EnrollUserAction $action)
    {
        try {
            $action->handle(auth()->user(), $course);

            return to_route('courses.show', $course->slug)
                ->with('success', 'Enrolled successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
