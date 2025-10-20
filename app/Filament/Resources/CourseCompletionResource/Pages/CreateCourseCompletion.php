<?php

declare(strict_types=1);

namespace App\Filament\Resources\CourseCompletionResource\Pages;

use App\Filament\Resources\CourseCompletionResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCourseCompletion extends CreateRecord
{
    protected static string $resource = CourseCompletionResource::class;
}
