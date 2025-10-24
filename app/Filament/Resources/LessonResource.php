<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('order')
                    ->numeric()
                    ->required()
                    ->default(1),
                TextInput::make('video_url')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->helperText('Full URL to video file (e.g., https://example.com/video.mp4)'),
                TextInput::make('duration_seconds')
                    ->numeric()
                    ->label('Duration (seconds)')
                    ->helperText('Optional: Video duration in seconds'),
                Toggle::make('is_free_preview')
                    ->label('Free Preview')
                    ->helperText('Allow guests to view this lesson without enrollment')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('order')
                    ->sortable()
                    ->label('Order'),
                TextColumn::make('duration_seconds')
                    ->formatStateUsing(fn ($state): string => $state ? gmdate('i:s', $state) : 'N/A')
                    ->label('Duration'),
                IconColumn::make('is_free_preview')
                    ->boolean()
                    ->label('Free'),
                TextColumn::make('progress_count')
                    ->counts('progress')
                    ->label('Views'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_free_preview')
                    ->label('Free Preview')
                    ->placeholder('All lessons')
                    ->trueLabel('Free preview only')
                    ->falseLabel('Enrolled only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'view' => Pages\ViewLesson::route('/{record}'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
