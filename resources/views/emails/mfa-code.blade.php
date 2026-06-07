<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #198754;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .code-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 3px dashed #198754;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 12px;
            color: #198754;
            font-family: 'Courier New', monospace;
            display: inline-block;
        }
        .code-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning strong {
            color: #856404;
        }
        .info-text {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin: 15px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
        .footer a {
            color: #198754;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .content {
                padding: 30px 20px;
            }
            .code {
                font-size: 32px;
                letter-spacing: 8px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 Salenga Farm</h1>
            <p>Security Verification</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $userName }}</strong>,
            </div>
            
            <p class="info-text">
                You are receiving this email because a login attempt was made to your Salenga Farm account. 
                To complete your login, please enter the verification code below:
            </p>
            
            <div class="code-box">
                <div class="code-label">Your Verification Code</div>
                <div class="code">{{ $code }}</div>
            </div>
            
            <div class="warning">
                <strong>⏱ Time Sensitive:</strong> This code will expire in <strong>{{ $expiresIn }} minutes</strong>. 
                Please use it immediately.
            </div>
            
            <p class="info-text">
                If you did not attempt to log in, please ignore this email. Your account remains secure, 
                and no further action is needed.
            </p>
            
            <p class="info-text">
                For security reasons, never share this code with anyone, including Salenga Farm staff.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Salenga Farm</strong><br>
                Quality Plants for Your Garden
            </p>
            <p style="margin: 0; font-size: 12px;">
                This is an automated security email. Please do not reply to this message.
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px;">
                Need help? Visit <a href="https://salengafarm.page">salengafarm.page</a>
            </p>
        </div>
    </div>
</body>
</html>
