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

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPost extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewPost Constructor.
     */
    public function __construct(public string $type, public User $user, public Post $post)
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
        if ($this->type == 'subscription') {
            return [
                'title' => $this->user->username.' 在你关注的主题下发送了新帖子',
                'body'  => $this->user->username.' 在你关注的主题 '.$this->post->topic->name.' 下发送了新帖',
                'url'   => sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
            ];
        }

        if ($this->type == 'staff') {
            return [
                'title' => $this->user->username.' 在员工论坛发送了一个帖子',
                'body'  => $this->user->username.' 在 '.$this->post->topic->name.' 回帖了 ',
                'url'   => sprintf('%s/posts/%s', route('topics.show', ['id' => $this->post->topic->id]), $this->post->id),
            ];
        }

        return [
            'title' => $this->user->username.' 在你创建的主题下回帖了',
            'body'  => $this->user->username.' 在你创建的主题 '.$this->post->topic->name.' 中发送了一个帖子 ',
            'url'   => sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
        ];
    }
}
