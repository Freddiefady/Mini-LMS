<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Enrollments';

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
                    ->limit(30)
                    ->label('Course'),
                TextColumn::make('course.level.name')
                    ->badge()
                    ->label('Level'),
                TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(function (Enrollment $record): string {
                        $progress = $record->course->getUserProgress($record->user);

                        return ((int) $progress).'%';
                    })
                    ->badge()
                    ->color(fn (Enrollment $record): string => match (true) {
                        $record->course->getUserProgress($record->user) === 100.0 => 'success',
                        $record->course->getUserProgress($record->user) >= 50.0 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(query: fn (Builder $query, string $direction): Builder =>
                        // Note: This is a simplified sort, actual implementation would need raw SQL
                    $query),
                TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Enrolled'),
                IconColumn::make('completed')
                    ->label('Completed')
                    ->boolean()
                    ->getStateUsing(fn (Enrollment $record): bool => $record->course->getUserProgress($record->user) === 100.0
                    ),
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
                Filter::make('completed')
                    ->label('Completed Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('enrolled_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Enrollments created through public site
    }
}
