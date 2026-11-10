<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
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
            <h1>USTED Resit Portal</h1>
            <p>University of Skills Training and Entrepreneurial Development</p>
        </div>
        <div class="content">
            <div class="greeting">Hello, {{ $user->full_name }}!</div>
            <p>You requested a password reset for the Campus Resit &amp; Supplementary Examination Portal.</p>
            <p>Enter this one-time code on the portal to continue resetting your password:</p>

            <div class="btn-wrapper" style="font-family:monospace;font-size:30px;font-weight:800;letter-spacing:8px;color:#881337;">
                {{ $pin }}
            </div>

            <div class="note">
                <strong>Notice:</strong> This code expires in <strong>10 minutes</strong> and can be used once. If you did not request a password reset, ignore this email; your password will remain unchanged.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} USTED Examination Directorate. All rights reserved.
        </div>
    </div>
</body>
</html>
