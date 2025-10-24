<?php

declare(strict_types=1);

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Lesson;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

final class ViewEnrollment extends ViewRecord
{
    protected static string $resource = EnrollmentResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Enrollment Details')
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Student Name'),
                    TextEntry::make('user.email')
                        ->label('Student Email')
                        ->copyable(),
                    TextEntry::make('course.title')
                        ->label('Course'),
                    TextEntry::make('course.level.name')
                        ->label('Level')
                        ->badge(),
                    TextEntry::make('enrolled_at')
                        ->dateTime()
                        ->label('Enrollment Date'),
                    TextEntry::make('progress')
                        ->label('Overall Progress')
                        ->state(fn (Enrollment $record): string => ((int) $record->course->getUserProgress($record->user)).'%')
                        ->badge()
                        ->color(fn (Enrollment $record): string => match (true) {
                            $record->course->getUserProgress($record->user) === 100.0 => 'success',
                            $record->course->getUserProgress($record->user) >= 50.0 => 'warning',
                            default => 'gray',
                        }),
                ])
                ->columns(2),

            Section::make('Lesson Progress')
                ->schema([
                    RepeatableEntry::make('course.lessons')
                        ->label('')
                        ->schema([
                            TextEntry::make('order')
                                ->label('Lesson'),
                            TextEntry::make('title')
                                ->label('Title'),
                            TextEntry::make('status')
                                ->label('Status')
                                ->state(function (Lesson $record, ViewEnrollment $livewire): string {
                                    $progress = $record->progress()
                                        ->where('user_id', $livewire->record->user_id)
                                        ->first();

                                    if (! $progress) {
                                        return 'Not Started';
                                    }
                                    if ($progress->completed_at) {
                                        return 'Completed';
                                    }

                                    return 'In Progress';
                                })
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Completed' => 'success',
                                    'In Progress' => 'warning',
                                    default => 'gray',
                                }),
                            TextEntry::make('watch_time')
                                ->label('Watch Time')
                                ->state(function (Lesson $record, ViewEnrollment $livewire): string {
                                    $progress = $record->progress()
                                        ->where('user_id', $livewire->record->user_id)
                                        ->first();

                                    return $progress && $progress->watch_seconds
                                        ? gmdate('i:s', (int) $progress->watch_seconds)
                                        : 'N/A';
                                }),
                        ])
                        ->columns(4),
                ]),
        ]);
    }
}
