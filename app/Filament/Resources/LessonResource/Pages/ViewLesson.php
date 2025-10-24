<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

final class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Lesson Information')
                ->schema([
                    TextEntry::make('course.title')
                        ->label('Course'),
                    TextEntry::make('title'),
                    TextEntry::make('order'),
                    TextEntry::make('video_url')
                        ->copyable()
                        ->url(fn ($state) => $state),
                    TextEntry::make('duration_seconds')
                        ->formatStateUsing(fn ($state): string => $state ? gmdate('H:i:s', $state) : 'N/A')
                        ->label('Duration'),
                    IconEntry::make('is_free_preview')
                        ->boolean()
                        ->label('Free Preview'),
                ])
                ->columns(2),

            Section::make('Statistics')
                ->schema([
                    TextEntry::make('progress_count')
                        ->label('Total Views')
                        ->state(fn ($record) => $record->progress()->count()),
                    TextEntry::make('completed_count')
                        ->label('Completed By')
                        ->state(fn ($record) => $record->progress()->whereNotNull('completed_at')->count()),
                    TextEntry::make('average_watch_time')
                        ->label('Avg Watch Time')
                        ->state(function ($record): string {
                            $avg = $record->progress()->avg('watch_seconds');

                            return $avg ? gmdate('i:s', $avg) : 'N/A';
                        }),
                ])
                ->columns(3),

            Section::make('Metadata')
                ->schema([
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime(),
                ])
                ->columns(2)
                ->collapsed(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
