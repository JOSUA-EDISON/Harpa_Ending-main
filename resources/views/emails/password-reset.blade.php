<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Baru</title>
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
        .header h2 {
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
        .password-box {
            background: #f0fdf4;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #2a9d8f;
        }
        .password {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 2px;
            font-weight: bold;
            color: #2a9d8f;
            margin: 0;
            padding: 10px;
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
            <h2>Password Baru Anda</h2>
        </div>
        <div class="content">
            <p>Halo {{ $user->name }},</p>
            <p>Password baru Anda telah digenerate:</p>

            <div class="password-box">
                <p class="password">{{ $password }}</p>
            </div>

            <div class="warning-box">
                <p>⚠️ Demi keamanan, segera ubah password ini setelah Anda berhasil login.</p>
            </div>

            <p>Silakan gunakan password ini untuk login ke sistem.</p>

            <div class="footer">
                <p>Email ini berisi informasi rahasia. Jangan teruskan email ini kepada siapapun.</p>
            </div>
        </div>
    </div>
</body>
</html>
