<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCompletionResource\Pages;
use App\Models\CourseCompletion;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class CourseCompletionResource extends Resource
{
    protected static ?string $model = CourseCompletion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCourseCompletions::route('/'),
            'create' => Pages\CreateCourseCompletion::route('/create'),
            'view' => Pages\ViewCourseCompletion::route('/{record}'),
            'edit' => Pages\EditCourseCompletion::route('/{record}/edit'),
        ];
    }
}
