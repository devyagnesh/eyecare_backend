<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Account Deletion Requested - Eyecare</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
        }
        .email-body h2 {
            color: #1f2937;
            font-size: 20px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        .email-body p {
            color: #4b5563;
            font-size: 16px;
            margin: 0 0 20px 0;
        }
        .info-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box p {
            color: #991b1b;
            font-size: 15px;
            margin: 0;
        }
        .info-box strong {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .warning-box p {
            color: #92400e;
            font-size: 14px;
            margin: 0;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
        .email-footer {
            padding: 30px;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .email-header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Account Deletion Requested</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $name }},</h2>
            
            <p>We have received your request to delete your account from the Eyecare Management System.</p>

            <div class="info-box">
                <p>
                    <strong>Important Information:</strong>
                    Your account will be permanently deleted on <strong>{{ $scheduledDeletionAt->format('F j, Y \a\t g:i A') }}</strong> ({{ $scheduledDeletionAt->diffForHumans() }}).
                </p>
            </div>

            <p>During this 30-day period, you can still access your account and cancel the deletion request if you change your mind. After the scheduled deletion date, all your data will be permanently removed and cannot be recovered.</p>

            <div class="warning-box">
                <p><strong>Warning:</strong> This action is irreversible. Once your account is deleted, all your data including:</p>
                <ul style="color: #92400e; margin: 10px 0; padding-left: 20px;">
                    <li>Your profile information</li>
                    <li>All associated records and data</li>
                    <li>Account history</li>
                </ul>
                <p style="margin-top: 10px;">will be permanently removed and cannot be restored.</p>
            </div>

            <div class="divider"></div>

            <p>If you did not request this account deletion, please contact our support team immediately to secure your account.</p>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p>Best regards,<br>
            <strong>Eyecare Management System</strong></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>This email was sent by Eyecare Management System</p>
            <p>Please do not reply to this email</p>
        </div>
    </div>
</body>
</html>

