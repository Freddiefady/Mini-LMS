@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
            @if($course->image_url)
                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
            @endif

            <div class="flex justify-between items-start mb-4">
                <div>
                <span class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full mb-2">
                    {{ $course->level->name }}
                </span>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
                </div>

                @auth
                    @if(!$enrolled)
                        <form method="POST" action="{{ route('enroll', $course->slug) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Enroll Now</button>
                        </form>
                    @else
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">Enrolled</span>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Login to Enroll</a>
                @endauth
            </div>

            <p class="text-gray-600 dark:text-gray-400 text-lg">{{ $course->description }}</p>
        </div>

        <!-- Alpine.js Feature #1: Collapsible Lesson Accordion -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Course Lessons</h2>

            <div x-data="{ openLesson: null }" class="space-y-2">
                @foreach($lessons as $lesson)
                    <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                        <button
                            @click="openLesson = openLesson === {{ $lesson->id }} ? null : {{ $lesson->id }}"
                            class="w-full flex justify-between items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500 dark:text-gray-400">{{ $lesson->order }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $lesson->title }}</span>
                                @if($lesson->is_free_preview)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Free Preview</span>
                                @endif
                            </div>
                            <svg
                                class="w-5 h-5 transition-transform duration-200"
                                :class="{ 'rotate-180': openLesson === {{ $lesson->id }} }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="openLesson === {{ $lesson->id }}"
                            x-transition
                            class="p-4 bg-gray-50 dark:bg-gray-700"
                        >
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Duration: {{ $lesson->duration_seconds ? gmdate('i:s', $lesson->duration_seconds) : 'N/A' }}</p>
                            <a href="{{ route('lessons.show', [$course->slug, $lesson->id]) }}" class="btn-primary inline-block">
                                Watch Lesson
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
