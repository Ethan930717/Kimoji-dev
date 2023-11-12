<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMaxWarningsReached extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user)
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
        $profileUrl = href_profile($this->user);

        return (new MailMessage())
            ->greeting('⚠️警告：H&R上限')
            ->line('你已达到H&R上限！下载权限已取消！')
            ->action('请尽快处理您的H&R种子！', $profileUrl)
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
            'title' => '⚠️警告：H&R上限',
            'body'  => '你已达到H&R上限！下载权限已取消！',
            'url'   => sprintf('/users/%s', $this->user->username),
        ];
    }
}
