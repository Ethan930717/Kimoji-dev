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

use App\Models\Torrent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUploadTip extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewUploadTip Constructor.
     */
    public function __construct(public string $type, public string $tipper, public int $amount, public Torrent $torrent)
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
            'title' => $this->tipper.' 向你上传的种子打赏了 '.$this->amount.' 魔力值',
            'body'  => $this->tipper.' 打赏了您上传的 '.$this->torrent->name,
            'url'   => sprintf('/torrents/%s', $this->torrent->id),
        ];
    }
}
