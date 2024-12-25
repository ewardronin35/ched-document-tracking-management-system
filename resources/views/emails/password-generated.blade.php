<!-- {{-- resources/views/emails/password_generated.blade.php --}} -->

<!DOCTYPE html>
<html>
<head>
    <title>Your New Password</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>Your password has been reset. Your new password is:</p>

    <p><strong>{{ $password }}</strong></p>

    <p>Please change your password after logging in.</p>

    <p>Best regards,<br/>{{ config('app.name') }}</p>
</body>
</html>
