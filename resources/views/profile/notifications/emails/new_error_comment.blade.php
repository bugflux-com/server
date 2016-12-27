<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New comment for error {{ $error->name }}</title>
</head>
<body>

<h3> Hello {{ $user->name }}! </h3>
<p><b>{{ $comment->user->name }}</b> commented error <b>{{ $error->name }}</b> in project <b>{{ $error->project->name }}.</b>: </p>
<p>{{ $comment->message }}</p>



</body>
</html>