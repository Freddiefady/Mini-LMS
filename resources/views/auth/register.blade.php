@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Register</h2>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <button type="submit" class="w-full btn-primary">
                    Register
                </button>
            </form>

            <p class="text-center text-gray-600 dark:text-gray-400 mt-4">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a>
            </p>
        </div>
    </div>
@endsection
