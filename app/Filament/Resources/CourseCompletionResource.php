<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCompletionResource\Pages;
use App\Filters\CompletedDateFilter;
use App\Models\CourseCompletion;
use App\Queries\FirstUserEnrollmentsQuery;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Pipeline;

final class CourseCompletionResource extends Resource
{
    protected static ?string $model = CourseCompletion::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Completions';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Student'),
                TextColumn::make('user.email')
                    ->searchable()
                    ->copyable()
                    ->label('Email'),
                TextColumn::make('course.title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->label('Course'),
                TextColumn::make('course.level.name')
                    ->badge()
                    ->color('success')
                    ->label('Level'),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Completed On'),
                TextColumn::make('duration')
                    ->label('Time Taken')
                    ->state(function (CourseCompletion $record, FirstUserEnrollmentsQuery $query): string {
                        $enrollment = $query->builder($record->course_id);

                        if (! $enrollment) {
                            return 'N/A';
                        }

                        $days = $enrollment->enrolled_at->diffInDays($record->completed_at);

                        return $days.' days';
                    })
                    ->sortable(false),
            ])
            ->filters([
                SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Course'),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Student'),
                Filter::make('completed_at')
                    ->form([
                        DatePicker::make('completed_from')
                            ->label('Completed From'),
                        DatePicker::make('completed_until')
                            ->label('Completed Until'),
                    ])
                    ->query(fn ($query, array $data) => Pipeline::send($query)
                        ->through([
                            new CompletedDateFilter($data['completed_from'] !== null ? (string) $data['completed_from'] : null),
                            new CompletedDateFilter($data['completed_until'] !== null ? (string) $data['completed_until'] : null, '<='),
                        ])
                        ->thenReturn()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('completed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseCompletions::route('/'),
            'view' => Pages\ViewCourseCompletion::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Completions created automatically
    }
}
