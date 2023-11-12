<?php

namespace App\Notifications;

use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPreWarning extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Torrent $torrent)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $profileUrl = href_profile($this->user);

        return (new MailMessage())
            ->greeting('⚠️警告：触发H&R规则')
            ->line('你的一个或多个种子触发了H&R警告')
            ->action('请尽快处理你的H&R种子', $profileUrl)
            ->line('感谢你使用'.config('other.title'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->torrent->name.' 触发了H&R',
            'body'  => '以下种子触发了H&R警告，请尽快处理 '.$this->torrent->name,
            'url'   => sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
