<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm your email address</title>
</head>
<body style="margin:0;padding:24px;background:#f8fafc;color:#1e293b;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:560px;margin:0 auto;overflow:hidden;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;">
        <div style="padding:28px 24px;background:linear-gradient(135deg,#440a15 0%,#640f1f 55%,#7a1326 100%);color:#ffffff;text-align:center;">
            <p style="margin:0;color:#fecdd3;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;">USTED</p>
            <h1 style="margin:9px 0 0;font-size:22px;line-height:1.3;">Confirm your email address</h1>
            <p style="margin:7px 0 0;color:#ffe4e6;font-size:13px;">Campus Resit &amp; Supplementary Examination Portal</p>
        </div>

        <div style="padding:30px 24px;line-height:1.6;font-size:14px;">
            <p style="margin:0 0 14px;">{{ $purposeText }}</p>
            <p style="margin:0 0 10px;font-weight:bold;color:#334155;">Enter this one-time PIN in the portal:</p>
            <div style="margin:0;padding:18px 12px;border:1px solid #fecdd3;border-radius:12px;background:#fff1f2;color:#881337;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:32px;font-weight:bold;letter-spacing:8px;">
                {{ $pin }}
            </div>
            <p style="margin:18px 0 0;color:#475569;">This PIN expires {{ $expiryText }} and can be used once. Do not share it with anyone.</p>

            <div style="margin-top:22px;padding:13px 15px;border-left:4px solid #f59e0b;border-radius:6px;background:#fffbeb;color:#92400e;font-size:12px;">
                If you did not request this confirmation, you can ignore this email. Your current sign-in email will not change unless the PIN is entered successfully.
            </div>
        </div>

        <div style="padding:16px 24px;border-top:1px solid #e2e8f0;background:#f1f5f9;color:#64748b;text-align:center;font-size:12px;">
            &copy; {{ date('Y') }} USTED Examination Directorate
        </div>
    </div>
</body>
</html>
