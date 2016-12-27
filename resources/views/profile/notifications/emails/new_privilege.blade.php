<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New permission</title>
</head>
<body>

<h3> Hello {{ $permission->user->name }}! </h3>
<p>You have just got new permisssion in project <b>{{ $permission->project->name }}</b>. Your role is <b>{{ $permission->group->name}}</b>. </p>

</body>
</html>