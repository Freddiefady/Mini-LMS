<?php

declare(strict_types=1);

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', HomeController::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['check.enrollment'])->group(function (): void {
    Route::get('courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('courses/{course:slug}/lessons/{lesson}', [LessonController::class, 'show'])
        ->name('lessons.show')
        ->middleware('auth');
});

Route::middleware(['auth'])->group(function (): void {
    Route::post('courses/{course:slug}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');

    Route::post('lessons/{lesson}/complete', [LessonController::class, 'complete'])
        ->name('lessons.complete');

    Route::post('lessons/{lesson}/watch-time', [LessonController::class, 'updateWatchTime'])
        ->name('lessons.watch-time');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(  // @phpstan-ignore-line
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
