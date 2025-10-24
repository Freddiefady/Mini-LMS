<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseCompletionResource\Pages;

use App\Filament\Resources\CourseCompletionResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

final class ViewCourseCompletion extends ViewRecord
{
    protected static string $resource = CourseCompletionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Completion Details')
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Student Name'),
                    TextEntry::make('user.email')
                        ->label('Student Email')
                        ->copyable(),
                    TextEntry::make('course.title')
                        ->label('Course Completed'),
                    TextEntry::make('course.level.name')
                        ->label('Level')
                        ->badge()
                        ->color('success'),
                    TextEntry::make('completed_at')
                        ->dateTime()
                        ->label('Completion Date'),
                    TextEntry::make('enrollment_date')
                        ->label('Enrolled On')
                        ->state(function ($record) {
                            $enrollment = $record->user->enrollments()
                                ->where('course_id', $record->course_id)
                                ->first();

                            return $enrollment ? $enrollment->enrolled_at->format('M d, Y H:i') : 'N/A';
                        }),
                    TextEntry::make('time_taken')
                        ->label('Time to Complete')
                        ->state(function ($record) {
                            $enrollment = $record->user->enrollments()
                                ->where('course_id', $record->course_id)
                                ->first();

                            if (! $enrollment) {
                                return 'N/A';
                            }

                            return $enrollment->enrolled_at->diffForHumans($record->completed_at, true);
                        })
                        ->badge()
                        ->color('warning'),
                ])
                ->columns(2),

            Section::make('Course Statistics')
                ->schema([
                    TextEntry::make('total_lessons')
                        ->label('Total Lessons')
                        ->state(fn ($record) => $record->course->lessons()->count()),
                    TextEntry::make('total_watch_time')
                        ->label('Total Watch Time')
                        ->state(function ($record): string {
                            $totalSeconds = $record->user->lessonProgress()
                                ->whereHas('lesson', fn ($q) => $q->where('course_id', $record->course_id))
                                ->sum('watch_seconds');

                            return $totalSeconds ? gmdate('H:i:s', $totalSeconds) : 'N/A';
                        }),
                    TextEntry::make('course_duration')
                        ->label('Course Duration')
                        ->state(function ($record): string {
                            $totalDuration = $record->course->lessons()->sum('duration_seconds');

                            return $totalDuration ? gmdate('H:i:s', $totalDuration) : 'N/A';
                        }),
                ])
                ->columns(3),
        ]);
    }
}
