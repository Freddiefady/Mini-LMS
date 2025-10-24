<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;

final class CheckCourseEnrollment
{
    public function handle(Request $request, Closure $next): mixed
    {
        $course = $request->route('course');

        if ($course instanceof Course) {
            $enrolled = auth()->check() && $course->enrollments()
                ->where('user_id', auth()->id())
                ->exists();

            view()->share('enrolled', $enrolled);
        }

        return $next($request);
    }
}
