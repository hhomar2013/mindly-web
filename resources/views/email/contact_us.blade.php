<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e1e1;
        }

        .header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
            color: #444;
            line-height: 1.6;
            text-align: right;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .label {
            font-weight: bold;
            color: #0d6efd;
            width: 30%;
        }

        .message-box {
            background-color: #f9f9f9;
            border-right: 4px solid #0d6efd;
            padding: 20px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .footer {
            background-color: #f4f7f6;
            color: #888;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p style="margin-top: 5px; opacity: 0.9;">إشعار رسالة جديدة من الموقع</p>
        </div>

        <div class="content">
            <h2 style="color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">بيانات المرسل</h2>

            <table class="info-table">
                <tr>
                    <td class="label">الاسم:</td>
                    <td>{{ $details['name'] }}</td>
                </tr>
                <tr>
                    <td class="label">البريد:</td>
                    <td><a href="mailto:{{ $details['email'] }}" style="color: #0d6efd; text-decoration: none;">{{ $details['email'] }}</a></td>
                </tr>
                <tr>
                    <td class="label">الموضوع:</td>
                    <td>{{ $details['subject'] }}</td>
                </tr>
            </table>

            <h2 style="color: #333; margin-top: 30px;">نص الرسالة:</h2>
            <div class="message-box">
                {{ $details['message'] }}
            </div>
        </div>

        <div class="footer">
            هذا البريد تم إرساله آلياً من نموذج الاتصال بموقع <strong>Mindly</strong>.<br>
            &copy; {{ date('Y') }} جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
