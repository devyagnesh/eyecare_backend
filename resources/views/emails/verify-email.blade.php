<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Email - Eyecare Management System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header .subtitle {
            margin-top: 8px;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .content {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #888;
            font-size: 13px;
        }
        .footer .company-name {
            font-weight: 600;
            color: #667eea;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }
        .link-fallback {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>👓 Eyecare Management</h1>
            <div class="subtitle">Professional Optical Store Management System</div>
        </div>
        
        <div class="email-body">
            <div class="greeting">Hello {{ $name }}!</div>
            
            <div class="content">
                <p>Welcome to Eyecare Management System! We're excited to have you join our platform designed specifically for optical store owners.</p>
                
                <p>To get started and access all features of your store management system, please verify your email address by clicking the button below:</p>
            </div>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">Verify Email Address</a>
            </div>
            
            <div class="info-box">
                <p><strong>⏰ Important:</strong> This verification link will expire in 60 minutes.</p>
                <p><strong>🔒 Security:</strong> If you did not create an account with us, please ignore this email.</p>
            </div>
            
            <div class="content" style="font-size: 14px; color: #888;">
                <p>If the button doesn't work, you can copy and paste the following link into your browser:</p>
            </div>
            
            <div class="link-fallback">
                {{ $verificationUrl }}
            </div>
            
            <div class="divider"></div>
            
            <div class="content" style="font-size: 14px; color: #666;">
                <p><strong>What's Next?</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Manage your inventory of frames and lenses</li>
                    <li>Track customer appointments and orders</li>
                    <li>Process prescriptions and eyewear fittings</li>
                    <li>Generate reports and analytics</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p class="company-name">Eyecare Management System</p>
            <p>Professional Solution for Optical Store Owners</p>
            <p style="margin-top: 15px; font-size: 12px;">© {{ date('Y') }} Eyecare Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
