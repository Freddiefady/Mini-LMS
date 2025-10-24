<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $lesson->title }}</title>
</head>
<body>
    <div class="container">
        <h1>{{ $lesson->title }}</h1>
        <div>{!! $lesson->content !!}</div>
    </div>
</body>
</html>
