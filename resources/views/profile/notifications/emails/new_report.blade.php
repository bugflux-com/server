<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New report for error {{ $report->error->name }}</title>
</head>
<body>

<h3> Hello {{ $user->name }}! </h3>
<p>New report in project <b>{{ $report->error->project->name }}</b> for error <b>{{ $report->error->name }}</b>. </p>

</body>
</html>