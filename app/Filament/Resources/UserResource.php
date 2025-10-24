<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filters\CreatedAtFilter;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Pipeline;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Users';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin')
                    ->sortable(),
                TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Enrollments')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('courseCompletions_count')
                    ->counts('courseCompletions')
                    ->label('Completed')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Joined')
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_admin')
                    ->label('Admin Users')
                    ->placeholder('All users')
                    ->trueLabel('Admins only')
                    ->falseLabel('Regular users only'),
                Filter::make('has_enrollments')
                    ->label('Has Enrollments')
                    ->query(fn ($query) => $query->has('enrollments')),
                Filter::make('has_completions')
                    ->label('Has Completions')
                    ->query(fn ($query) => $query->has('courseCompletions')),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Joined From'),
                        DatePicker::make('created_until')
                            ->label('Joined Until'),
                    ])
                    ->query(fn ($query, array $data) => Pipeline::send($query)
                        ->through([
                            new CreatedAtFilter($data['created_from']),
                            new CreatedAtFilter($data['created_until'], '<='),
                        ])
                        ->thenReturn()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Users register through the public site
    }

    public static function canEdit($record): bool
    {
        return false; // Read-only for now
    }

    public static function canDelete($record): bool
    {
        return false; // Prevent accidental deletion
    }
}
