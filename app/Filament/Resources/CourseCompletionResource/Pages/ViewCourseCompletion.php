<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseCompletionResource\Pages;

use App\Filament\Resources\CourseCompletionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewCourseCompletion extends ViewRecord
{
    protected static string $resource = CourseCompletionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
