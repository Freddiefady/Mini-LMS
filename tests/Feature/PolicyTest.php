<?php

declare(strict_types=1);

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Level;
use App\Models\User;

it('user cannot modify another users progress', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $level = Level::factory()->create();
    $course = Course::factory()->create(['level_id' => $level->id]);
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    $progress = LessonProgress::create([
        'user_id' => $user1->id,
        'lesson_id' => $lesson->id,
    ]);

    $this->actingAs($user2);

    expect($user2->can('update', $progress))->toBeFalse();
});

it('admin can access filament panel', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/admin/login');

    // Admin should be redirected to the admin dashboard when authenticated
    $response->assertStatus(302);
});

it('regular user cannot access filament panel', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/admin');

    $response->assertForbidden();
});
