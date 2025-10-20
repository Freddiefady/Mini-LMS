<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseCompletionResource\Pages;

use App\Filament\Resources\CourseCompletionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditCourseCompletion extends EditRecord
{
    protected static string $resource = CourseCompletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
