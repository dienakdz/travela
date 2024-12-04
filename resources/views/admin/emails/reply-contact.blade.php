<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi liên hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
        }

        .message {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <h1>Phản hồi từ Quản trị viên Website Travela</h1>
        <p class="message">
            {!! $messageContent !!}
        </p>
    </div>
</body>
</html>
