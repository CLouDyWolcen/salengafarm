<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BrevoEmailService;
use Illuminate\Support\Facades\Mail;

class TestBrevoSetup extends Command
{
    protected $signature = 'email:test {email} {--mode=both : Test mode: smtp, brevo, or both}';
    protected $description = 'Test email configuration with delivery verification';

    public function handle()
    {
        $email = $this->argument('email');
        $mode = $this->option('mode');
        
        $this->info('Testing email configuration...');
        $this->info('Environment: ' . app()->environment());
        $this->info('From Address: ' . config('mail.from.address'));
        $this->info('From Name: ' . config('mail.from.name'));
        
        // Test Brevo API
        if ($mode === 'brevo' || $mode === 'both') {
            $this->info('');
            $this->info('Testing Brevo API...');
            
            try {
                $brevoService = new BrevoEmailService();
                
                if (!$brevoService->isConfigured()) {
                    $this->warn('⚠ Brevo API key not configured - running in local development mode');
                }
                
                $result = $brevoService->sendEmail(
                    $email,
                    'Test Email - Brevo API Setup - ' . now()->format('H:i:s'),
                    '<h1>Brevo API Test</h1><p>This email was sent via Brevo API at ' . now() . '</p><p>If you receive this, your Brevo API is working correctly!</p>'
                );
                
                if ($result['success']) {
                    if (isset($result['mode']) && $result['mode'] === 'local_development') {
                        $this->info('✓ Brevo API test successful (Local Development Mode)');
                        $this->info('  Check your Laravel logs to see the email content');
                    } else {
                        $this->info('✓ Brevo API test successful');
                        $this->info('  Message ID: ' . ($result['messageId'] ?? 'N/A'));
                        $this->info('  Check your inbox at: ' . $email);
                    }
                } else {
                    $this->error('✗ Brevo API test failed: ' . ($result['error'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                $this->error('✗ Brevo API test failed: ' . $e->getMessage());
            }
        }
        
        // Test Laravel Mail (SMTP/Log)
        if ($mode === 'smtp' || $mode === 'both') {
            $this->info('');
            $this->info('Testing Laravel Mail (SMTP/Log)...');
            
            try {
                Mail::raw('Test email via Laravel Mail. Configuration is working! Sent at: ' . now(), function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Test Email - Laravel Mail Setup - ' . now()->format('H:i:s'));
                });
                
                $mailer = config('mail.default');
                if ($mailer === 'log') {
                    $this->info('✓ Laravel Mail test successful (Log Mode)');
                    $this->info('  Check storage/logs/laravel.log to see the email');
                } else {
                    $this->info('✓ Laravel Mail test successful (SMTP Mode)');
                    $this->info('  Check your inbox at: ' . $email);
                }
            } catch (\Exception $e) {
                $this->error('✗ Laravel Mail test failed: ' . $e->getMessage());
            }
        }
        
        $this->info('');
        $this->info('📧 Email testing completed!');
        
        if (config('mail.default') === 'log') {
            $this->info('💡 Tip: You\'re in log mode. Check storage/logs/laravel.log for email content');
        }
        
        // Show configuration summary
        $this->info('');
        $this->info('Current Configuration:');
        $this->info('  MAIL_MAILER: ' . config('mail.default'));
        $this->info('  MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('  BREVO_API_KEY: ' . (env('BREVO_API_KEY') ? 'Configured' : 'Not configured'));
        
        return 0;
    }
}