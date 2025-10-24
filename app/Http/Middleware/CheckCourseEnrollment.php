<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckCourseEnrollment
{
    public function handle(Request $request, Closure $next): Response
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
