<?php

namespace App\Notifications;

use App\Models\Ban;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBan extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Ban $ban)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $chatdUrl = config('unit3d.chat-link-url');

        return (new MailMessage())
            ->greeting('你被流放了')
            ->line('由于'.$this->ban->ban_reason.'，你被'.config('other.title').'流放了')
            ->action('请求帮助', $chatdUrl)
            ->line('感谢你造访'.config('other.title'));
    }
}
