@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Available Courses</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($course->image_url)
                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-purple-500"></div>
                    @endif

                    <div class="p-6">
                    <span class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full mb-2">
                        {{ $course->level->name }}
                    </span>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($course->description, 100) }}</p>
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn-primary w-full text-center">
                            View Course
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
