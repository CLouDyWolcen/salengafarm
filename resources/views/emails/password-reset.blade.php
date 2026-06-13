<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Salenga Farm</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #198754 0%, #157347 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">
                                🔐 Password Reset Request
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 20px 0;">
                                Hello <strong>{{ $userName }}</strong>,
                            </p>

                            <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 20px 0;">
                                You are receiving this email because we received a password reset request for your account at <strong>Salenga Farm</strong>.
                            </p>

                            <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 30px 0;">
                                Click the button below to reset your password:
                            </p>

                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $resetUrl }}" 
                                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #198754 0%, #157347 100%); color: #ffffff; text-decoration: none; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 30px 0; border-radius: 4px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 14px; color: #856404; line-height: 1.5;">
                                            <strong>⚠️ Important:</strong> This password reset link will expire in <strong>60 minutes</strong>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #666666; line-height: 1.6; margin: 20px 0;">
                                If the button doesn't work, copy and paste this link into your browser:
                            </p>

                            <p style="font-size: 12px; color: #198754; word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 3px solid #198754;">
                                {{ $resetUrl }}
                            </p>

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 30px 0 20px 0; border-radius: 4px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 14px; color: #721c24; line-height: 1.5;">
                                            <strong>🛡️ Security Notice:</strong> If you did not request a password reset, please ignore this email or contact us immediately. Your password will not be changed unless you click the reset link above.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #666666; line-height: 1.6; margin: 0;">
                                Thank you,<br>
                                <strong style="color: #198754;">The Salenga Farm Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #6c757d;">
                                This email was sent to: <strong>{{ $email }}</strong>
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #6c757d;">
                                © {{ date('Y') }} Salenga Farm. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Additional Footer Note -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td style="text-align: center; padding: 10px;">
                            <p style="font-size: 11px; color: #999999; margin: 0;">
                                This is an automated email. Please do not reply to this message.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
