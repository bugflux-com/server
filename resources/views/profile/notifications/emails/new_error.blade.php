<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New error in project {{ $error->project->name }}</title>
</head>
<body>

<h3> Hello {{ $user->name }}! </h3>
<p>New error was reported in project <b>{{ $error->project->name }}</b>. The error is <b>{{ $error->name }}</b>. </p>

</body>
</html>