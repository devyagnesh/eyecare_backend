<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Eyecare Management System</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333335;
            background-color: #f7f8f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, rgb(132, 90, 223) 0%, rgb(107, 73, 181) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333335;
            font-size: 20px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        .email-body p {
            color: #536485;
            font-size: 14px;
            margin: 0 0 20px 0;
            line-height: 1.8;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, rgb(132, 90, 223) 0%, rgb(107, 73, 181) 100%);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
            transition: opacity 0.3s;
        }
        .reset-button:hover {
            opacity: 0.9;
        }
        .email-footer {
            background: #f7f8f9;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #f3f3f3;
        }
        .email-footer p {
            color: #8c9097;
            font-size: 12px;
            margin: 5px 0;
        }
        .warning-box {
            background: rgba(245, 184, 73, 0.1);
            border-left: 4px solid rgb(245, 184, 73);
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box p {
            color: rgb(120, 90, 40);
            font-size: 13px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>EYECARE</h1>
        </div>
        
        <div class="email-body">
            <h2>Reset Your Password</h2>
            
            <p>Hello {{ $name }},</p>
            
            <p>We received a request to reset your password for your Eyecare Management System account. If you didn't make this request, you can safely ignore this email.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
            </div>
            
            <p>Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #536485; font-size: 12px; background: #f7f8f9; padding: 10px; border-radius: 4px;">{{ $resetUrl }}</p>
            
            <div class="warning-box">
                <p><strong>Security Notice:</strong> This password reset link will expire in 60 minutes. For security reasons, please do not share this link with anyone.</p>
            </div>
            
            <p>If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>
        </div>
        
        <div class="email-footer">
            <p><strong>Eyecare Management System</strong></p>
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Eyecare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

