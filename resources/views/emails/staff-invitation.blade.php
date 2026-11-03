<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your staff invitation</title>
</head>
<body style="margin:0;background:#f1f5f9;color:#0f172a;font-family:Arial,sans-serif;">
    <div style="max-width:600px;margin:32px auto;padding:32px;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;">
        <p style="margin:0;color:#2563eb;font-size:13px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;">Campus Resit Portal</p>
        <h1 style="margin:16px 0 8px;font-size:24px;">You have been invited to join the staff portal</h1>
        <p style="margin:0;line-height:1.6;color:#475569;">An Administrator invited you to create a <strong>{{ $roleTitle }}</strong> account.</p>
        <p style="margin:24px 0 8px;font-size:14px;font-weight:bold;">Your one-time invitation code</p>
        <p style="margin:0;padding:16px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:10px;font-family:monospace;font-size:20px;letter-spacing:2px;word-break:break-all;">{{ $invitationCode }}</p>
        <p style="margin:24px 0;line-height:1.6;color:#475569;">Use the email address this message was sent to and enter the code on the invitation page. The code expires on <strong>{{ $expiryText }}</strong> and can be used once.</p>
        <p style="margin:28px 0;"><a href="{{ $invitationUrl }}" style="display:inline-block;padding:12px 20px;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:bold;border-radius:9px;">Accept invitation</a></p>
        <p style="margin:0;line-height:1.6;color:#64748b;font-size:13px;">If you were not expecting this invitation, contact your school Administrator. Do not forward or share this code.</p>
    </div>
</body>
</html>
