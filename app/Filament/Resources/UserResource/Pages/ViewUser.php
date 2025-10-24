<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\CourseCompletion;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

final class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('User Information')
                ->schema([
                    TextEntry::make('name')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight('bold'),
                    TextEntry::make('email')
                        ->copyable()
                        ->icon('heroicon-m-envelope'),
                    IconEntry::make('is_admin')
                        ->boolean()
                        ->label('Administrator'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Joined'),
                ])
                ->columns(),

            Section::make('Learning Statistics')
                ->schema([
                    TextEntry::make('enrollments_count')
                        ->label('Total Enrollments')
                        ->state(fn (User $record): int => $record->enrollments()->count())
                        ->badge()
                        ->color('primary'),
                    TextEntry::make('completions_count')
                        ->label('Courses Completed')
                        ->state(fn (User $record): int => $record->courseCompletions()->count())
                        ->badge()
                        ->color('success'),
                    TextEntry::make('in_progress_count')
                        ->label('In Progress')
                        ->state(function (User $record): int {
                            $enrolled = $record->enrollments()->count();
                            $completed = $record->courseCompletions()->count();

                            return max(0, $enrolled - $completed);
                        })
                        ->badge()
                        ->color('warning'),
                    TextEntry::make('completion_rate')
                        ->label('Completion Rate')
                        ->state(function (User $record): string {
                            $enrolled = $record->enrollments()->count();
                            if ($enrolled === 0) {
                                return '0%';
                            }

                            $completed = $record->courseCompletions()->count();

                            return round(($completed / $enrolled) * 100).'%';
                        })
                        ->badge()
                        ->color(function ($state): string {
                            $percent = (int) str_replace('%', '', $state);

                            return match (true) {
                                $percent >= 75 => 'success',
                                $percent >= 50 => 'warning',
                                default => 'gray',
                            };
                        }),
                    TextEntry::make('total_watch_time')
                        ->label('Total Watch Time')
                        ->state(function (User $record): string {
                            $totalSeconds = $record->lessonProgress()->sum('watch_seconds');
                            if ($totalSeconds === 0) {
                                return '0h 0m';
                            }

                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);

                            return "{$hours}h {$minutes}m";
                        })
                        ->badge()
                        ->color('info'),
                    TextEntry::make('lessons_completed')
                        ->label('Lessons Completed')
                        ->state(fn (User $record): int => $record->lessonProgress()->whereNotNull('completed_at')->count())
                        ->badge(),
                ])
                ->columns(3),

            Section::make('Enrolled Courses')
                ->schema([
                    RepeatableEntry::make('enrollments')
                        ->label('')
                        ->schema([
                            TextEntry::make('course.title')
                                ->label('Course')
                                ->weight('bold'),
                            TextEntry::make('course.level.name')
                                ->label('Level')
                                ->badge(),
                            TextEntry::make('enrolled_at')
                                ->dateTime()
                                ->label('Enrolled On'),
                            TextEntry::make('progress')
                                ->label('Progress')
                                ->state(fn (Enrollment $record): string => $record->course->getUserProgress($record->user).'%')
                                ->badge()
                                ->color(function ($state): string {
                                    $percent = str_replace('%', '', $state);

                                    return match (true) {
                                        $percent === 100 => 'success',
                                        $percent >= 50 => 'warning',
                                        default => 'gray',
                                    };
                                }),
                            TextEntry::make('status')
                                ->label('Status')
                                ->state(function (Enrollment $record): string {
                                    $isCompleted = $record->user->courseCompletions()
                                        ->where('course_id', $record->course_id)
                                        ->exists();

                                    if ($isCompleted) {
                                        return 'Completed';
                                    }

                                    $progress = $record->course->getUserProgress($record->user);
                                    if ($progress === 0) {
                                        return 'Not Started';
                                    }

                                    return 'In Progress';
                                })
                                ->badge()
                                ->color(fn ($state): string => match ($state) {
                                    'Completed' => 'success',
                                    'In Progress' => 'warning',
                                    default => 'gray',
                                }),
                        ])
                        ->columns(5),
                ])
                ->collapsed(false),

            Section::make('Completed Courses')
                ->schema([
                    RepeatableEntry::make('courseCompletions')
                        ->label('')
                        ->schema([
                            TextEntry::make('course.title')
                                ->label('Course')
                                ->weight('bold'),
                            TextEntry::make('course.level.name')
                                ->label('Level')
                                ->badge()
                                ->color('success'),
                            TextEntry::make('completed_at')
                                ->dateTime()
                                ->label('Completed On'),
                            TextEntry::make('duration')
                                ->label('Time Taken')
                                ->state(function (CourseCompletion $record) {
                                    $enrollment = $record->user->enrollments()
                                        ->where('course_id', $record->course_id)
                                        ->first();

                                    if (! $enrollment) {
                                        return 'N/A';
                                    }

                                    return $enrollment->enrolled_at->diffForHumans($record->completed_at, true);
                                }),
                        ])
                        ->columns(4),
                ])
                ->collapsed()
                ->visible(fn (User $record): bool => $record->courseCompletions()->count() > 0),

            Section::make('Recent Activity')
                ->schema([
                    RepeatableEntry::make('lessonProgress')
                        ->label('')
                        ->schema([
                            TextEntry::make('lesson.course.title')
                                ->label('Course')
                                ->limit(30),
                            TextEntry::make('lesson.title')
                                ->label('Lesson')
                                ->limit(40),
                            TextEntry::make('watch_seconds')
                                ->label('Watch Time')
                                ->formatStateUsing(fn ($state): string => gmdate('i:s', $state)),
                            IconEntry::make('completed_at')
                                ->label('Completed')
                                ->boolean()
                                ->getStateUsing(fn ($record): bool => ! is_null($record->completed_at)),
                            TextEntry::make('updated_at')
                                ->label('Last Activity')
                                ->since(),
                        ])
                        ->columns(5)
                        ->state(fn (User $record) => $record->lessonProgress()
                            ->latest('updated_at')
                            ->limit(10)
                            ->get()
                        ),
                ])
                ->collapsed(),
        ]);
    }
}
