<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #7f1d1d, #991b1b);
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #fecaca;
        }
        .content {
            padding: 32px 24px;
            line-height: 1.6;
            font-size: 14px;
        }
        .greeting {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background-color: #881337;
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: none;
            padding: 13px 32px;
            border-radius: 12px;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(136, 19, 55, 0.3);
        }
        .footer {
            background-color: #f1f5f9;
            padding: 16px 24px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .note {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            font-size: 12px;
            color: #92400e;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AMMUSTED Resit Portal</h1>
            <p>Akenten Appiah-Menka University of Skills Training and Entrepreneurial Development</p>
        </div>
        <div class="content">
            <div class="greeting">Hello, {{ $user->full_name }}!</div>
            <p>You recently submitted a request to reset your password for the Campus Resit & Supplementary Examination Portal.</p>
            <p>Please tap the button below on your device to create a new password:</p>
            
            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn">Reset My Password</a>
            </div>

            <div class="note">
                <strong>Notice:</strong> This password reset link will expire in <strong>60 minutes</strong>. If you did not request a password change, no further action is required and your account remains secure.
            </div>

            <p style="font-size: 12px; color: #64748b; word-break: break-all;">
                If you have trouble clicking the button, copy and paste this link into your browser:<br>
                <a href="{{ $resetUrl }}" style="color: #881337;">{{ $resetUrl }}</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AMMUSTED Examination Directorate. All rights reserved.
        </div>
    </div>
</body>
</html>
