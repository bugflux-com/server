<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invalid login attempt</title>
</head>
<body>

<h3> Hello {{ $user->name }}! </h3>
<p>Someone tried to login to your account using invalid credentials! IP: {{ $ip }}</p>

</body>
</html>