<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New user</title>
</head>
<body>

@if(!empty($name))
    <h3> Hello {{ $name }}! </h3>
@endif
<p>Welcome to bugflux! </p>
<p>You should have received email with passsword reset link.</p>

</body>
</html>