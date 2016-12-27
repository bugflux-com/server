<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New email confirmation</title>
</head>
<body>

<h3> Hello {{ Auth::user()->name }}! </h3>
<p>You wanted to change you email address to this one on your bugflux account. </p>
<p>Here is confirmation <a href="{{ route('profile.confirm.newemail', $token) }}"> link</a>. </p>
<p>If you don't have bugflux account or you didn't want to change email ignore this message.</p>

</body>
</html>