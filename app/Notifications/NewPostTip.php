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
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPostTip extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewPostTip Constructor.
     */
    public function __construct(public string $type, public string $tipper, public int $amount, public Post $post)
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
        return [
            'title' => $this->tipper.' 向你打赏了 '.$this->amount.' 魔力',
            'body'  => $this->tipper.' 在 '.$this->post->topic->name.' 打赏了你',
            'url'   => sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
        ];
    }
}
