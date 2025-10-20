<x-mail::message>
    # Course Completed! 🎉

    Congratulations {{ $user->name }}!

    You have successfully completed the course **{{ $course->title }}**. Well done on your dedication and hard work!

    <x-mail::button :url="route('courses.show', $course->slug)">
        View Certificate
    </x-mail::button>

    Keep learning and exploring more courses!<br>
    {{ config('app.name') }} Team
</x-mail::message>
