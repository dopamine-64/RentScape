<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Registration Alert - RentScape</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px 30px;
        }

        h2 {
            color: #2563eb;
            margin-bottom: 10px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .info {
            background: #f3f4f6;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 15px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Greeting -->
        <h2>Hello {{ $user->name }},</h2>

        <!-- Main Message -->
        <p>You have successfully registered into <strong>RentScape</strong>.</p>

        <!-- Account Info -->
        <div class="info">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Time:</strong> {{ now()->format('F j, Y, g:i A') }}</p>
        </div>

        <!-- Warning -->
        <p>If this was not you, please secure your account immediately.</p>

        <!-- Footer -->
        <div class="footer">
            — RentScape Team
        </div>
    </div>
</body>
</html>