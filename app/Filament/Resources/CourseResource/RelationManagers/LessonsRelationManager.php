<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('order')
                    ->numeric()
                    ->required()
                    ->default(fn (): int => ((int) $this->getOwnerRecord()->lessons()->max('order')) + 1),
                TextInput::make('video_url')
                    ->url()
                    ->required()
                    ->maxLength(255),
                TextInput::make('duration_seconds')
                    ->numeric()
                    ->label('Duration (seconds)'),
                Toggle::make('is_free_preview')
                    ->label('Free Preview')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('order')->sortable(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('duration_seconds')
                    ->formatStateUsing(fn ($state): string => $state ? gmdate('i:s', (int) $state) : 'N/A'),
                IconColumn::make('is_free_preview')
                    ->boolean()
                    ->label('Free Preview'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
