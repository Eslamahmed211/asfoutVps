<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userNotification extends Notification
{
    use Queueable;
    private $content ;

    /**
     * Create a new notification instance.
     */
    public function __construct($content)
    {
      $this->content = $content ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
      return ['database'];
    }

  
    public function toArray(object $notifiable): array
    {
      return [
        'message' => $this->content['message'],
        'type' => $this->content['type'],
        'order_id' => $this->content['order_id'],
        'newStatus' => $this->content['newStatus'],
    ];
    }
}
