<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Warning;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserManualWarningExpire extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Warning $warning)
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
            ->greeting('手动警告已到期！')
            ->line('你的警告已到期！')
            ->action('查看个人资料', $profileUrl)
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
            'title' => '手动警告已过期',
            'body'  => '你由于 '.$this->warning->reason.' 被警告，该警告目前已到期 ',
            'url'   => sprintf('/users/%s', $this->user->username),
        ];
    }
}
