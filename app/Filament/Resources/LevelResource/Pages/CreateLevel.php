<?php

declare(strict_types=1);

namespace App\Filament\Resources\LevelResource\Pages;

use App\Filament\Resources\LevelResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLevel extends CreateRecord
{
    protected static string $resource = LevelResource::class;
}
