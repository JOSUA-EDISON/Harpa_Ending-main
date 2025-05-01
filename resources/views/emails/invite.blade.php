<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Undangan untuk Bergabung dengan Harpa</title>
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
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #2a9d8f 0%, #1a7f72 100%);
            color: #fff;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #718096;
            margin-top: 20px;
        }
        .highlight-box {
            background-color: #f0fdf4;
            border-left: 4px solid #2a9d8f;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .expiry-notice {
            color: #e53e3e;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Undangan Bergabung dengan Harpa</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Anda telah diundang oleh Administrator untuk bergabung dengan aplikasi Harpa sebagai <strong>{{ ucfirst($invitation->role) }}</strong>.</p>

            <div class="highlight-box">
                <p>Silakan klik tombol di bawah ini untuk menerima undangan dan menyelesaikan proses registrasi menggunakan akun Google Anda:</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $inviteUrl }}" class="button">Terima Undangan</a>
            </div>

            <p class="expiry-notice">⏰ Undangan ini akan kedaluwarsa pada: <strong>{{ $invitation->expires_at->format('d M Y, H:i') }}</strong></p>

            <p>Jika Anda tidak mengenali undangan ini, silakan abaikan email ini.</p>

            <p>Terima kasih,<br>Tim Harpa</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Harpa. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
