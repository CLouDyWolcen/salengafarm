<?php
/**
 * Complete Email System Test
 * Run this to test both Brevo API and email delivery
 */

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\BrevoEmailService;
use Illuminate\Support\Facades\Log;

echo "=== SALENGA FARM EMAIL SYSTEM TEST ===\n\n";

// Test 1: Check configuration
echo "1. CHECKING CONFIGURATION:\n";
echo "   BREVO_API_KEY: " . (env('BREVO_API_KEY') ? 'SET ✓' : 'NOT SET ✗') . "\n";
echo "   MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "   MAIL_FROM_NAME: " . env('MAIL_FROM_NAME') . "\n\n";

// Test 2: Test Brevo API
echo "2. TESTING BREVO API:\n";
try {
    $brevoService = new BrevoEmailService();
    
    $testEmail = 'farmsalenga@gmail.com'; // Change this to your test email
    $subject = 'Test Email - Brevo API Integration - ' . date('Y-m-d H:i:s');
    $htmlContent = '
    <html>
    <body style="font-family: Arial, sans-serif;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background: #2d5d31; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
                <h1>🌿 Salenga Farm</h1>
                <p>Email System Test</p>
            </div>
            <div style="background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6;">
                <h2>Email Test Successful!</h2>
                <p>This email was sent via Brevo API to test your email system configuration.</p>
                <p><strong>Test Details:</strong></p>
                <ul>
                    <li>Sent at: ' . date('Y-m-d H:i:s') . '</li>
                    <li>API: Brevo Transactional Email API</li>
                    <li>From: ' . env('MAIL_FROM_ADDRESS') . '</li>
                    <li>System: Salenga Farm Plant Request System</li>
                </ul>
                <p>If you received this email, your Brevo configuration is working correctly!</p>
            </div>
            <div style="background: #e9ecef; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; font-size: 14px; color: #6c757d;">
                <p>Salenga Farm Email System Test</p>
            </div>
        </div>
    </body>
    </html>';
    
    $result = $brevoService->sendEmail($testEmail, $subject, $htmlContent);
    
    if ($result['success']) {
        echo "   ✓ Brevo API test SUCCESSFUL\n";
        echo "   Message ID: " . $result['messageId'] . "\n";
        echo "   Email sent to: $testEmail\n\n";
    } else {
        echo "   ✗ Brevo API test FAILED\n";
        echo "   Error: " . $result['error'] . "\n\n";
    }
} catch (Exception $e) {
    echo "   ✗ Brevo API test FAILED\n";
    echo "   Exception: " . $e->getMessage() . "\n\n";
}

// Test 3: Check if email reaches inbox
echo "3. EMAIL DELIVERY CHECK:\n";
echo "   Please check your email inbox at: $testEmail\n";
echo "   Look for the test email with subject: '$subject'\n";
echo "   \n";
echo "   If you DON'T receive the email, possible issues:\n";
echo "   - Email might be in spam/junk folder\n";
echo "   - Sender email (" . env('MAIL_FROM_ADDRESS') . ") needs to be verified in Brevo\n";
echo "   - Domain authentication might be needed\n\n";

// Test 4: Check logs
echo "4. CHECKING RECENT LOGS:\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $recentLogs = array_slice(explode("\n", $logs), -10);
    foreach ($recentLogs as $log) {
        if (strpos($log, 'Brevo') !== false || strpos($log, 'email') !== false) {
            echo "   " . trim($log) . "\n";
        }
    }
} else {
    echo "   No log file found\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Check your email inbox for the test email\n";
echo "2. If no email received, verify sender email in Brevo dashboard\n";
echo "3. Test the admin interface email sending functionality\n";