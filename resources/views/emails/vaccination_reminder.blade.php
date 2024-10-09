<!DOCTYPE html>
<html>
<head>
    <title>Vaccination Reminder</title>
</head>
<body>
<h1>Dear {{ $name }},</h1>
<p>This is a reminder that you are scheduled for your COVID vaccination tomorrow, on {{ $scheduled_date }}.</p>
<p>Location: {{ $center }}</p>
<p>Please make sure to arrive on time and bring your identification documents.</p>
<p>Thank you for registering!</p>
</body>
</html>
