<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New comment for report {{ $report->name }}</title>
</head>
<body>

<h3> Hello {{ $user->name }}! </h3>
<p><b>{{ $comment->user->name }}</b> commented report <b>{{ $report->name }}</b> in project <b>{{ $error->project->name }}.</b>: </p>
<p>{{ $comment->message }}</p>


</body>
</html>