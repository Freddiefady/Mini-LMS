<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@lms.test',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create regular user
        $user = User::query()->create([
            'name' => 'John Doe',
            'email' => 'user@lms.test',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create levels
        $beginner = Level::query()->create([
            'name' => 'Beginner',
            'description' => 'Perfect for those just starting out',
            'sort_order' => 1,
        ]);

        $intermediate = Level::query()->create([
            'name' => 'Intermediate',
            'description' => 'For those with some experience',
            'sort_order' => 2,
        ]);

        $advanced = Level::query()->create([
            'name' => 'Advanced',
            'description' => 'For experienced learners',
            'sort_order' => 3,
        ]);

        // Create courses
        $course1 = Course::query()->create([
            'level_id' => $beginner->id,
            'title' => 'Introduction to Laravel',
            'slug' => 'intro-to-laravel-'.now()->timestamp,
            'description' => 'Learn the basics of Laravel framework from scratch. This comprehensive course covers routing, controllers, views, and more.',
            'image_url' => 'https://picsum.photos/800/400?random=1',
            'status' => 'published',
        ]);

        $course2 = Course::query()->create([
            'level_id' => $intermediate->id,
            'title' => 'Advanced PHP Techniques',
            'slug' => 'advanced-php-'.now()->timestamp,
            'description' => 'Master advanced PHP concepts including OOP, design patterns, and best practices for building scalable applications.',
            'image_url' => 'https://picsum.photos/800/400?random=2',
            'status' => 'published',
        ]);

        $course3 = Course::query()->create([
            'level_id' => $advanced->id,
            'title' => 'Microservices Architecture',
            'slug' => 'microservices-arch-'.now()->timestamp,
            'description' => 'Deep dive into microservices architecture patterns, distributed systems, and containerization with Docker.',
            'image_url' => 'https://picsum.photos/800/400?random=3',
            'status' => 'published',
        ]);

        // Create lessons for Course 1
        Lesson::query()->create([
            'course_id' => $course1->id,
            'title' => 'Getting Started with Laravel',
            'order' => 1,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => 600,
            'is_free_preview' => true,
        ]);

        Lesson::query()->create([
            'course_id' => $course1->id,
            'title' => 'Understanding Routes and Controllers',
            'order' => 2,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'duration_seconds' => 720,
            'is_free_preview' => false,
        ]);

        Lesson::query()->create([
            'course_id' => $course1->id,
            'title' => 'Working with Eloquent ORM',
            'order' => 3,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'duration_seconds' => 840,
            'is_free_preview' => false,
        ]);

        Lesson::query()->create([
            'course_id' => $course1->id,
            'title' => 'Building Views with Blade',
            'order' => 4,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'duration_seconds' => 660,
            'is_free_preview' => false,
        ]);

        // Create lessons for Course 2
        Lesson::query()->create([
            'course_id' => $course2->id,
            'title' => 'Object-Oriented Programming Fundamentals',
            'order' => 1,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'duration_seconds' => 900,
            'is_free_preview' => true,
        ]);

        Lesson::query()->create([
            'course_id' => $course2->id,
            'title' => 'Design Patterns in PHP',
            'order' => 2,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
            'duration_seconds' => 1080,
            'is_free_preview' => false,
        ]);

        Lesson::query()->create([
            'course_id' => $course2->id,
            'title' => 'Dependency Injection',
            'order' => 3,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
            'duration_seconds' => 780,
            'is_free_preview' => false,
        ]);

        // Create lessons for Course 3
        Lesson::query()->create([
            'course_id' => $course3->id,
            'title' => 'Microservices Overview',
            'order' => 1,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
            'duration_seconds' => 1200,
            'is_free_preview' => true,
        ]);

        Lesson::query()->create([
            'course_id' => $course3->id,
            'title' => 'Service Communication Patterns',
            'order' => 2,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
            'duration_seconds' => 960,
            'is_free_preview' => false,
        ]);

        Lesson::query()->create([
            'course_id' => $course3->id,
            'title' => 'Docker and Containerization',
            'order' => 3,
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
            'duration_seconds' => 1440,
            'is_free_preview' => false,
        ]);

        // Enroll user in first course
        Enrollment::query()->create([
            'user_id' => $user->id,
            'course_id' => $course1->id,
        ]);

        $this->command->info('Seeding completed!');
        $this->command->info('Admin: admin@lms.test / password');
        $this->command->info('User: user@lms.test / password');
    }
}
