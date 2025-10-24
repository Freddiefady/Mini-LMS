<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px;">
        <h1 style="color: #2d3748; margin-top: 0;">Course Completed! 🎉</h1>
        
        <p>Congratulations {{ $user->name }}!</p>
        
        <p>You have successfully completed the course <strong>{{ $course->title }}</strong>. Well done on your dedication and hard work!</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('courses.show', $course->slug) }}" style="display: inline-block; background-color: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 500;">View Certificate</a>
        </div>
        
        <p>Keep learning and exploring more courses!<br>
        {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
