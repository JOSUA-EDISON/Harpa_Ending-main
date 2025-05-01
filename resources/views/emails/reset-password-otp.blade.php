<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            margin: 0;
            padding: 0;
            background-color: #f7fafc;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2a9d8f 0%, #1a7f72 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .otp-box {
            background: #f0fdf4;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #2a9d8f;
        }
        .otp-code {
            font-size: 32px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #2a9d8f;
            margin: 0;
            padding: 10px;
            font-family: 'Courier New', monospace;
        }
        .warning-box {
            background-color: #fff7ed;
            border-left: 4px solid #ed8936;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #718096;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Code</h1>
        </div>
        <div class="content">
            <p>Hello {{ $name ?? 'there' }},</p>
            <p>You've requested to reset your password. Use the following verification code to complete the process:</p>

            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <div class="warning-box">
                <p>⏰ This code will expire in 60 minutes.</p>
            </div>

            <p>If you didn't request this password reset, please ignore this email.</p>

            <div class="footer">
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
