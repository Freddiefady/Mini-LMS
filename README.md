# Mini-LMS Platform

A full-featured Learning Management System built with Laravel 11, Livewire 3, Alpine.js, Tailwind CSS, Filament v3, and Plyr.js.

## Features ✨

### Public Features
- 🏠 Home page with published courses
- 📚 Course detail pages with lesson listings
- 🎥 Video lessons with Plyr player
- 👁️ Free preview lessons for guests
- 🔐 User registration with welcome emails

### Student Features
- 📝 Course enrollment (authenticated users only)
- 📊 Progress tracking per lesson
- ✅ Lesson completion marking
- 🏆 Course completion certificates
- 📧 Automatic completion emails
- 🎯 Progress percentage display

### Admin Panel (Filament v3)
- 📋 CRUD operations for Levels, Courses, Lessons
- 👥 User directory (read-only)
- 📈 Enrollment management with progress tracking
- 🎖️ Course completion records
- 📊 Dashboard with statistics widget
- 🔄 Drag-and-drop lesson reordering

### Interactive UI (Alpine.js)
- 🎵 Collapsible lesson accordion
- ✔️ Confirmation modal before completion
- 📈 Animated progress bars
- 🎬 Plyr integration with Alpine lifecycle
- 🌙 Global dark mode toggle

### Technical Excellence
- 🏗️ Action pattern for business logic
- 🔒 Database transaction handling
- ⚡ Concurrency management
- 🛡️ Authorization policies
- ✅ Comprehensive Pest tests
- 🎯 Idempotent operations
- 🌍 UTC timestamps with timezone display

## Installation 🚀

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL
- Mail server (or Mailtrap for testing)

### Setup Steps

```bash
# Clone repository
git clone https://github.com/Freddiefady/Mini-LMS
cd Mini-LMS

# Install PHP dependencies
composer setup
composer dev

# Seed database with demo data
php artisan db:seed
```

### Seeded Credentials

**Admin User:**
- Email: `admin@lms.test`
- Password: `password`

**Regular User:**
- Email: `user@lms.test`
- Password: `password`

### Seeded Data
- ✅ 3 Levels (Beginner, Intermediate, Advanced)
- ✅ 3 Published courses with lessons
- ✅ Free preview lessons enabled
- ✅ 1 pre-enrolled user

## Running Tests 🧪

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/EnrollmentTest.php

# Run Pest tests
./vendor/bin/pest

# Run with parallel execution
php artisan test --parallel
```

### Test Coverage
- ✅ User registration with welcome email
- ✅ Course enrollment (authenticated only)
- ✅ Free preview access for guests
- ✅ Lesson progress tracking
- ✅ Course completion detection
- ✅ Email idempotency
- ✅ Database constraints
- ✅ Transactional consistency
- ✅ Authorization policies
- ✅ User data isolation

## Database Schema 📊

### ERD Overview

```
users
├── id
├── name
├── email
├── password
├── is_admin
└── timestamps

levels
├── id
├── name
├── description
├── sort_order
└── timestamps

courses
├── id
├── level_id (FK)
├── title
├── slug (unique)
├── description
├── image_url
├── status (draft|published)
└── timestamps

lessons
├── id
├── course_id (FK)
├── title
├── order (unique per course)
├── video_url
├── duration_seconds
├── is_free_preview
└── timestamps

enrollments
├── id
├── user_id (FK)
├── course_id (FK)
├── enrolled_at
└── UNIQUE(user_id, course_id)

lesson_progress
├── id
├── user_id (FK)
├── lesson_id (FK)
├── started_at
├── completed_at
├── watch_seconds
├── timestamps
└── UNIQUE(user_id, lesson_id)

course_completions
├── id
├── user_id (FK)
├── course_id (FK)
├── completed_at
├── timestamps
└── UNIQUE(user_id, course_id)
```
Contributing 🤝

Fork the repository
Create feature branch (git checkout -b feature/AmazingFeature).

Commit changes (git commit -m 'Add AmazingFeature').

Push to branch (git push origin feature/AmazingFeature).

Open Pull Request.

-------------------------
 Made with ❤️ for the Laravel Community
