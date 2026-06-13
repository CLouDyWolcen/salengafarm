<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class BrevoChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Call the toBrevo method on the notification
        if (method_exists($notification, 'toBrevo')) {
            return $notification->toBrevo($notifiable);
        }
    }
}
