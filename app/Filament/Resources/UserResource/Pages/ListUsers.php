<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users'),
            'admins' => Tab::make('Admins')
                ->modifyQueryUsing(fn ($query) => $query->isAdmin())
                ->badge(fn () => User::isAdmin()->count()),
            'students' => Tab::make('Students')
                ->modifyQueryUsing(fn ($query) => $query->notAdmin())
                ->badge(fn () => User::notAdmin()->count()),
            'active' => Tab::make('Active Learners')
                ->modifyQueryUsing(fn ($query) => $query->has('enrollments'))
                ->badge(fn () => User::query()->has('enrollments')->count()),
        ];
    }
}
