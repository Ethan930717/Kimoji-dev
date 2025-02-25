<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Notifications;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewTopic extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewTopic Constructor.
     */
    public function __construct(public string $type, public User $user, public Topic $topic)
    {
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type == 'staff') {
            return [
                'title' => $this->user->username.' 在员工板块中发送了新帖子',
                'body'  => $this->user->username.' 在 '.$this->topic->forum->name.' 创建了新主题',
                'url'   => route('topics.show', ['id' => $this->topic->id]),
            ];
        }

        return [
            'title' => $this->user->username.' 在你关注的板块创建了新主题',
            'body'  => $this->user->username.' 在 '.$this->topic->forum->name.' 创建了新主题 ',
            'url'   => sprintf('/forums/topics/%s', $this->topic->id),
        ];
    }
}
