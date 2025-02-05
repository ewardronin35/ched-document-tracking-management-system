<!DOCTYPE html>
<html>
<head>
    <title>Document Status Update</title>
</head>
<body>
    <h1>Document Status Update</h1>
    <p>Dear {{ $document->full_name }},</p>
    <p>Your document with Tracking Number: <strong>{{ $document->tracking_number }}</strong> 
       has been <strong>{{ $document->approval_status }}</strong>.</p>
    <p>Thank you for using our service.</p>

    <hr>
    <small>This is an automated message. Please do not reply.</small>
</body>
</html>
