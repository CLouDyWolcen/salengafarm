<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Services\BrevoEmailService;
use Illuminate\Support\Facades\Log;

class CustomResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['brevo'];
    }

    /**
     * Send via Brevo (custom channel).
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function toBrevo($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $brevoService = new BrevoEmailService();

        $userName = $notifiable->first_name ?? $notifiable->name ?? 'User';
        
        // Create email HTML
        $emailHtml = view('emails.password-reset', [
            'resetUrl' => $resetUrl,
            'userName' => $userName,
            'email' => $notifiable->email
        ])->render();

        $result = $brevoService->sendEmail(
            $notifiable->email,
            'Password Reset Request - Salenga Farm',
            $emailHtml
        );

        if ($result['success']) {
            Log::info('Password reset email sent via Brevo', [
                'email' => $notifiable->email,
                'user_id' => $notifiable->id ?? null,
                'message_id' => $result['messageId'] ?? 'unknown'
            ]);
        } else {
            Log::error('Failed to send password reset email via Brevo', [
                'email' => $notifiable->email,
                'user_id' => $notifiable->id ?? null,
                'error' => $result['error'] ?? 'unknown'
            ]);
        }

        return $result['success'];
    }
}
