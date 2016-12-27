<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Changed role in project</title>
</head>
<body>

<h3> Hello {{ $permission->user->name }}! </h3>
<p>Your role in project <b>{{ $permission->project->name }}</b> has changed. Now you are <b>{{ $permission->group->name}}</b>. </p>

</body>
</html>