<?php

namespace App\Services;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BrevoEmailService
{
    protected $apiInstance;
    protected $isConfigured;

    public function __construct()
    {
        $apiKey = env('BREVO_API_KEY');
        $this->isConfigured = !empty($apiKey);
        
        if ($this->isConfigured) {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
            $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
        }
    }

    public function sendEmail($to, $subject, $htmlContent, $fromEmail = null, $fromName = null, $attachmentPath = null)
    {
        // If Brevo is not configured (local development), log the email instead
        if (!$this->isConfigured) {
            Log::info('Email would be sent via Brevo (Local Development Mode)', [
                'to' => $to,
                'subject' => $subject,
                'from_email' => $fromEmail ?? config('mail.from.address'),
                'from_name' => $fromName ?? config('mail.from.name'),
                'has_attachment' => !empty($attachmentPath),
                'html_content' => substr($htmlContent, 0, 200) . '...'
            ]);
            
            return [
                'success' => true, 
                'messageId' => 'local-dev-' . uniqid(),
                'mode' => 'local_development'
            ];
        }

        try {
            $fromEmail = $fromEmail ?? config('mail.from.address');
            $fromName = $fromName ?? config('mail.from.name');

            // Validate sender email
            if (empty($fromEmail)) {
                throw new \Exception('Sender email is required');
            }

            $emailData = [
                'subject' => $subject,
                'sender' => ['name' => $fromName, 'email' => $fromEmail],
                'to' => [['email' => $to]],
                'htmlContent' => $htmlContent
            ];

            // Add attachment if provided
            if ($attachmentPath && Storage::exists($attachmentPath)) {
                $fileContent = Storage::get($attachmentPath);
                $fileName = basename($attachmentPath);
                
                $emailData['attachment'] = [[
                    'content' => base64_encode($fileContent),
                    'name' => $fileName
                ]];
            }

            $sendSmtpEmail = new SendSmtpEmail($emailData);
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            
            Log::info('Brevo API email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'messageId' => $result->getMessageId(),
                'has_attachment' => !empty($attachmentPath),
                'sender' => $fromEmail
            ]);

            return ['success' => true, 'messageId' => $result->getMessageId()];
        } catch (\Exception $e) {
            Log::error('Brevo API email failed', [
                'to' => $to,
                'subject' => $subject,
                'sender' => $fromEmail ?? 'unknown',
                'error' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function isConfigured()
    {
        return $this->isConfigured;
    }
}
