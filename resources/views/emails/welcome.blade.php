<x-mail::message>
    # Welcome to Our Learning Platform!

    Hi {{ $user->name }},

    Thank you for joining us! You now have access to amazing courses designed to help you grow.

    <x-mail::button :url="route('home')">
        Start Learning
    </x-mail::button>

    Happy learning!<br>
    {{ config('app.name') }} Team
</x-mail::message>
