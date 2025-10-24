<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\Enrollment;
use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $totalCourses = Course::query()->count();
        $totalLessons = Lesson::query()->count();
        $totalEnrollments = Enrollment::query()->count();
        $totalCompletions = CourseCompletion::query()->count();

        // Calculate average completion rate
        $avgCompletion = $totalEnrollments > 0
            ? round(($totalCompletions / $totalEnrollments) * 100, 1)
            : 0;

        return [
            Stat::make('Total Courses', $totalCourses)
                ->description('Published and draft courses')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('success'),

            Stat::make('Total Lessons', $totalLessons)
                ->description('Published and draft courses')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('Amer'),

            Stat::make('Total Enrollments', $totalEnrollments)
                ->description('All time enrollments')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Course Completions', $totalCompletions)
                ->description("{$avgCompletion}% completion rate")
                ->descriptionIcon('heroicon-o-trophy')
                ->color('warning'),
        ];
    }
}
