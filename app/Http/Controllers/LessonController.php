<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Progress\CompleteLessonAction;
use App\Actions\Progress\StartLessonAction;
use App\Actions\Progress\UpdateWatchTimeAction;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson, StartLessonAction $action): View
    {
        // Check authorization
        Gate::authorize('view', $lesson);

        // Start tracking progress
        $action->handle(auth()->user(), $lesson);

        $progress = $lesson->progress()
            ->where('user_id', auth()->id())
            ->first();

        $nextLesson = $lesson->getNext();
        $prevLesson = $lesson->getPrevious();

        return view('lessons.show', [
            'lesson' => $lesson,
            'progress' => $progress,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'course' => $course,
        ]);
    }

    public function complete(Request $request, Lesson $lesson, CompleteLessonAction $action)
    {
        $watchSeconds = $request->integer('watch_seconds');

        $action->handle(auth()->user(), $lesson, $watchSeconds);

        return response()->json(['success' => true]);
    }

    public function updateWatchTime(Request $request, Lesson $lesson, UpdateWatchTimeAction $action)
    {
        $seconds = $request->integer('seconds');

        $action->handle(auth()->user(), $lesson, $seconds);

        return response()->json(['success' => true]);
    }
}
