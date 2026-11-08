<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $broadcast->title }} - USTED Resit Portal</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header Band (Official USTED Wine Gradient) -->
        <tr>
            <td style="background: linear-gradient(135deg, #440a15 0%, #7a1326 50%, #9b1c31 100%); padding: 32px 28px; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase;">USTED Campus Resit Portal</h1>
                <p style="margin: 6px 0 0; font-size: 12px; color: #fbe6e9; letter-spacing: 0.2px;">Official Notice from the Chief Examiner's Office</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 32px 28px;">
                <p style="font-size: 15px; line-height: 1.5; margin: 0 0 16px; color: #0f172a;">
                    Dear <strong>{{ $student->full_name }}</strong> (Index No: <strong>{{ $student->student_id_number ?? 'Candidate' }}</strong>),
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 20px;">
                    The Chief Examiner has issued a new institutional update regarding the upcoming supplementary and resit examination session:
                </p>

                <!-- Announcement Callout Box -->
                <div style="background-color: #fdf3f4; border-left: 4px solid #7a1326; border-radius: 8px; padding: 20px; margin: 24px 0;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #7a1326; letter-spacing: 0.5px; margin-bottom: 6px;">
                        Announcement Notice
                    </div>
                    <h2 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 10px;">
                        {{ $broadcast->title }}
                    </h2>
                    <div style="font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-line;">
                        {{ $broadcast->message }}
                    </div>
                </div>

                <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 24px 0 28px;">
                    Please log into your student portal dashboard to review full scheduling updates, timetable dates, or your registered courses.
                </p>

                <!-- CTA Button -->
                <div style="text-align: center; margin: 28px 0 12px;">
                    <a href="{{ route('login') }}" 
                       style="display: inline-block; background-color: #7a1326; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 10px; box-shadow: 0 2px 4px rgba(122, 19, 38, 0.2);">
                        Open Student Portal Dashboard &rarr;
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 20px 28px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8; line-height: 1.5;">
                <p style="margin: 0 0 4px; font-weight: 600; color: #64748b;">Department of ICT Education • Examination Directorate</p>
                <p style="margin: 0;">University of Skills Training and Entrepreneurial Development (USTED)</p>
            </td>
        </tr>
    </table>
</body>
</html>
