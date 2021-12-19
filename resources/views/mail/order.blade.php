<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Roboto, Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ html_entity_decode($details['body']) }}</p>

    <p>Thank you!</p>
</body>
</html>
