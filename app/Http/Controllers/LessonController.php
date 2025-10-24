<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Progress\CompleteLessonAction;
use App\Actions\Progress\StartLessonAction;
use App\Actions\Progress\UpdateWatchTimeAction;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson, StartLessonAction $action): View
    {
        $user = auth()->user();

        abort_unless($user !== null, 401, 'Unauthorized');

        // Check authorization
        Gate::authorize('view', $lesson);

        // Start tracking progress
        $action->handle($user, $lesson);

        $progress = $lesson->progress()
            ->where('user_id', $user)
            ->first();

        $nextLesson = $lesson->getNextLesson();
        $prevLesson = $lesson->getPreviousLesson();

        return view('lessons.show', [
            'lesson' => $lesson,
            'progress' => $progress,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'course' => $course,
        ]);
    }

    public function complete(Request $request, Lesson $lesson, CompleteLessonAction $action): JsonResponse
    {
        $user = auth()->user();

        abort_unless($user !== null, 401, 'Unauthorized');

        $watchSeconds = $request->integer('watch_seconds');

        $action->handle($user, $lesson, $watchSeconds);

        return response()->json(['success' => true]);
    }

    public function updateWatchTime(Request $request, Lesson $lesson, UpdateWatchTimeAction $action): JsonResponse
    {
        $user = auth()->user();

        abort_unless($user !== null, 401, 'Unauthorized');

        $seconds = $request->integer('seconds');

        $action->handle($user, $lesson, $seconds);

        return response()->json(['success' => true]);
    }
}
