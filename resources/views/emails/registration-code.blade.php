<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Salenga Farm</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #198754 0%, #157347 100%); padding: 40px; text-align: center; border-radius: 8px 8px 0 0;">
                            <div style="width: 80px; height: 80px; background-color: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="white"/>
                                </svg>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600;">Welcome to Salenga Farm!</h1>
                            <p style="margin: 10px 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Verify your email to get started</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 40px 20px;">
                            <p style="margin: 0 0 20px; color: #333333; font-size: 16px; line-height: 1.5;">
                                Hello <strong>{{ $userName }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px; color: #666666; font-size: 15px; line-height: 1.6;">
                                Thank you for creating an account with Salenga Farm! To complete your registration and start exploring our products, please verify your email address by entering the code below:
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Verification Code -->
                    <tr>
                        <td style="padding: 0 40px 20px; text-align: center;">
                            <table role="presentation" style="margin: 0 auto; background-color: #f8f9fa; border: 2px solid #198754; border-radius: 12px; padding: 30px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 15px; color: #666666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                            Your Verification Code
                                        </p>
                                        <div style="font-size: 48px; font-weight: bold; color: #198754; letter-spacing: 12px; font-family: 'Courier New', monospace; line-height: 1;">
                                            {{ $code }}
                                        </div>
                                        <p style="margin: 15px 0 0; color: #999999; font-size: 13px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 5px;">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="#999999"/>
                                            </svg>
                                            Code expires in {{ $expiresIn }} minutes
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Instructions -->
                    <tr>
                        <td style="padding: 0 40px 30px;">
                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; margin: 20px 0;">
                                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.5;">
                                    <strong>⚠️ Security Note:</strong> If you didn't create an account with Salenga Farm, please ignore this email. Your email address will not be used without verification.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px 40px; border-radius: 0 0 8px 8px; border-top: 1px solid #e9ecef;">
                            <p style="margin: 0 0 10px; color: #999999; font-size: 13px; text-align: center; line-height: 1.5;">
                                This is an automated email. Please do not reply to this message.
                            </p>
                            <p style="margin: 0; color: #999999; font-size: 13px; text-align: center; line-height: 1.5;">
                                © {{ date('Y') }} Salenga Farm. All rights reserved.
                            </p>
                            <p style="margin: 10px 0 0; color: #cccccc; font-size: 12px; text-align: center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 5px;">
                                    <path d="M12 2L3 7V10C3 16 7 21 12 22C17 21 21 16 21 10V7L12 2Z" stroke="#cccccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 12L11 14L15 10" stroke="#cccccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Secure Email Verification
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
