<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CourseController extends Controller
{
    public function show(Request $request, Course $course): View
    {
        $enrolled = $request->get('is_enrolled', false);

        $lessons = $course->lessons()
            ->when($enrolled, function ($q): void {
                $q->freePreview();
            })
            ->get();

        return view('courses.show', ['course' => $course, 'enrolled' => $enrolled, 'lessons' => $lessons]);
    }
}
