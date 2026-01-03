<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Alert</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>You have successfully logged into <strong>RentScape</strong>.</p>

    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Time:</strong> {{ now() }}</p>

    <br>

    <p>If this was not you, please secure your account immediately.</p>

    <p>— RentScape Team</p>
</body>
</html>
