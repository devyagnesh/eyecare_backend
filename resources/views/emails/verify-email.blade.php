<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Email - Eyecare</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }
        .verify-button:hover {
            opacity: 0.9;
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }
        .link-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            word-break: break-all;
        }
        .link-container p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        .link-container a {
            color: #667eea;
            text-decoration: none;
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
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
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
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .email-header {
                padding: 30px 20px;
            }
            .verify-button {
                padding: 12px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Verify Your Email Address</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $name }},</h2>
            
            <p>Thank you for registering with Eyecare Management System. To complete your registration and start using your account, please verify your email address by clicking the button below.</p>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">Verify Email Address</a>
            </div>

            <p>If the button doesn't work, you can copy and paste the following link into your browser:</p>

            <div class="link-container">
                <p><a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>
            </div>

            <div class="divider"></div>

            <div class="warning-box">
                <p><strong>Important:</strong> This verification link will expire in 60 minutes. If you didn't create an account, please ignore this email.</p>
            </div>

            <p>If you're having trouble verifying your email, please contact our support team for assistance.</p>

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

