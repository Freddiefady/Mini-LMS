<?php

declare(strict_types=1);

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;
}
