<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
</head>
<body>
    <h2 style="color: #333;">{{ $title }}</h2>
    <p>{{ $salutation }}</p>
    <p>{{ $body }}</p>
    <p>
        <a href="{{ $resetUrl }}" style="background-color: #007bff; color: #ffffff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Reset Your Password
        </a>
    </p>

    <p>Thank you.</p>
    <p>Best regards,</p>
    <p>Hockey App</p>

    <hr style="margin-top: 20px;">
    <small style="color: #888;">This is an automated message. Please do not reply to this email.</small>
</body>
</html>
